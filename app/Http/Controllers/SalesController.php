<?php

namespace App\Http\Controllers;

use DataTable;
use App\Models\User;
use App\Models\Sales;
use App\Models\Product;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private function getSalesQuery()
    {
        return Sales::with([
            'details' => function ($q) {
                $q->select('id', 'sales_id', 'product_id', 'quantity');
            },
            'details.product' => function ($q) {
                $q->select('id', 'name', 'price');
            },
            'user' => function ($q) {
                $q->select('id', 'username');
            }
        ])->select('id', 'date', 'user_id', 'grand_total');
    }


    private function getProductQuery()
    {
        return Product::all();
    }

    private function dataTableResponse($query)
    {
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user_name', fn($row) => optional($row->user)->username ?? '-')
            ->addColumn('product_list', fn($row) => $row->product_list )
            ->editColumn('grand_total', fn($row) =>
                'Rp. ' . number_format($row->grand_total, 0, ',', '.')
            )
            ->filterColumn('product_list', function($query, $keyword) {
                $query->whereHas('details.product', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('action', function($row){

                return '<a href="'.route('product.sales.edit', $row->id).'" class="btn btn-warning edit btn-sm" data-id="'.$row->id.'">
                        <i class="fas fa-edit"></i> Edit</a>
                        <a href="javascript:void(0)" class="btn btn-danger deletes" data-id="'.$row->id.'" data-url="'.route('product.sales.delete', $row->id).'">
                        <i class="fas fa-trash"></i> Delete
                        </a>';
            })
            ->make(true);
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {

            if($request->has('get_total')){
                $total = Sales::sum('grand_total');
                return response()->json(['total' => $total]);
            }
            $query = $this->getSalesQuery();

            return $this->dataTableResponse($query);
        }

    // Untuk render awal view (non-AJAX)
    $sales = Sales::with('details.product')->get();

    $products = Product::all();

    return view('pages.product.sales.index', compact('sales', 'products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = $this->getProductQuery();
        $sales = new Sales;
        $disabled = false;

        return view('pages.product.sales.create-edit', compact('products','sales','disabled'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'product_id'   => 'required|array',
        'product_id.*' => 'exists:products,id',
        'quantity'     => 'required|array',
        'quantity.*'   => 'numeric|min:1',
        'price'        => 'nullable|array',
        'total_price'  => 'nullable|array',
        'sales_id'     => 'nullable|exists:sales,id'
    ]);

    DB::beginTransaction();
    try {

        // CREATE / GET SALES
        if (!empty($validated['sales_id'])) {
            $sales = Sales::where('id', $validated['sales_id'])
                ->where('user_id', auth()->id())
                ->lockForUpdate()
                ->firstOrFail();
        } else {
            $sales = Sales::create([
                'date'    => now(),
                'user_id' => auth()->id(),
            ]);
        }

        $grandTotal = 0;

        foreach ($validated['product_id'] as $index => $productId) {

            $qty = $validated['quantity'][$index];

            $product = Product::findOrFail($productId);

            $total = $product->price * $qty;

            SalesDetail::create([
                'sales_id'    => $sales->id,
                'product_id'  => $product->id,
                'quantity'    => $qty,
                'price'       => $product->price,
                'total_price' => $total
            ]);

            $grandTotal += $total;
        }

        // UPDATE GRAND TOTAL SEKALI
        $sales->update([
            'grand_total' => $sales->details()->sum('total_price')
        ]);

        DB::commit();

        return response()->json([
            'status'     => 'OK',
            'message'    => 'Sales added successfully',
            'sales_id'    => $sales->id,
            'grand_total' => $sales->grand_total
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Failed to process sales',
            'line'    => $e->getLine(),
            'error'   => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}


    // public function storeFinal(Request $request)
    // {
    //     $salesId = $request->sales_id;

    //     $sales = Sales::findOrFail($salesId);
    //     $sales->grand_total = $sales->details()->sum('total_price');
    //     $sales->save();

    //     return response()->json(['message' => 'Sales finalized successfully']);
    // }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    private function dataTableResponseEdit($query)
    {
        return DataTables::of($query)
            ->addColumn('product_name', fn($row) => $row->product->name)
            ->addColumn('quantity', function($row){
                return $row->quantity;
            })
            ->addColumn('unit_price', fn($row) =>
                'Rp. ' . number_format($row->price, 0, ',', '.')
            )
            ->editColumn('total_price', fn($row) =>
                'Rp. ' . number_format($row->total_price, 0, ',', '.')
            )
            ->filterColumn('product_list', function($query, $keyword) {
                $query->whereHas('details.product', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('action', function($row){
                return '<a href="javascript:void(0)" class="btn btn-danger deletes" data-id="'.$row->id.'" data-url="'.route('product.sales.delete-details', $row->id).'">
                        <i class="fas fa-trash"></i> Delete
                        </a>';
            })
            ->make(true);
    }

    public function edit(Request $request, string $id)
    {
        if ($request->ajax()) {
            $query = SalesDetail::with(['product', 'sales.user'])->where('sales_id', $id);
            return $this->dataTableResponseEdit($query);
        }

        // Untuk render awal view (non-AJAX)
        // Untuk non-AJAX
        $sales = Sales::with([
            'details' => function($q) {
                $q->select('id', 'sales_id', 'product_id', 'quantity', 'price', 'total_price')
                  ->with('product:id,name,price');
            },
            'user:id,name'
        ])
        ->findOrFail($id);
        $products = Product::select('id', 'name', 'price')->get();
        $grandTotal = 'Rp. ' . number_format($sales->grand_total ?? 0, 0, ',', '.');
        $disabled = 'false';
        return view('pages.product.sales.create-edit', compact(['products', 'id', 'grandTotal', 'sales', 'disabled']));

    }


    public function select2product(Request $request)
    {
        $data = [];

        $search = $request->q;

        if(!empty($search)){
            $data = Product::select("id", "name", "price")
            ->where(function($query) use($search){
            $query->where('name', 'LIKE', '%'. $search .'%')
            ->orWhere('price', 'LIKE', '%'. $search .'%');
            })
            ->get();

        return response()->json($data);
        }
    }

    /**
     * Update the specified resource in storage.
     */


public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'product_id'   => 'required|array',
        'product_id.*' => 'numeric|exists:products,id',
        'quantity'     => 'required|array',
        'quantity.*'   => 'numeric|min:1',
        'price'        => 'nullable|array',
        'total_price'  => 'nullable|array'
    ]);

    DB::beginTransaction();
    try {
        // Ambil sales utama
        $sales = Sales::findOrFail($id);

        // Hapus detail lama, lalu insert ulang (lebih aman)
        $sales->details()->delete();

        $grandTotal = 0;

        foreach ($validated['product_id'] as $index => $productId) {
            $qty   = $validated['quantity'][$index];
            $price = $validated['price'][$index] ?? 0;
            $total = $validated['total_price'][$index] ?? ($qty * $price);

            info("Index: $index, ProductID: $productId, Qty: $qty, Price: $price, Total: $total");

            $sales->details()->create([
                'product_id'  => $productId,
                'quantity'    => $qty,
                'price'       => $price,
                'total_price' => $total,
            ]);

            $grandTotal += $total;

            info("GrandTotal saat ini: $grandTotal");

        }


        // Update grand total di tabel sales
        $sales->update([
            'grand_total' => $grandTotal
        ]);

        DB::commit();

        return response()->json([
            'status'  => 'OK',
            'message' => 'Sales updated successfully',
            // 'data'    => [
            //     'sales_id'     => $sales->id,
            //     'grand_total'  => $grandTotal
            // ]
        ], 200);

    } catch (\Throwable $th) {
        DB::rollBack();
        return response()->json([
            'status'  => 'error',
            'message' => 'Update failed',
    ]);
    }
}


    public function updateInline(Request $request , string $id)
    {
        $detail = SalesDetail::findOrFail($id);

        $field = $request->field;
        $value = $request->value;

        if(in_array($field, ['quantity'])){
            $detail->$field = $value;

            $detail->total_price = $value * $detail->product->price;
            $detail->save();

            return response()->json(['message' => 'Updated Successfully']);

        }

        return response()->json(['message' => 'invalid field'], 400);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       Sales::where('id', $id)->delete();
       SalesDetail::where('sales_id', $id)->delete();
        return response()->json(['message' => 'Deleted Successfully']);
    }

    public function destroyDetail(string $id){
        $detail = SalesDetail::findOrFail($id);

        $sales = Sales::find($detail->sales_id);

        if($sales){
            $sales->grand_total -= $detail->total_price;
            $sales->save();
        }

        // Hapus detail
        $detail->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }
}
