<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if($request->ajax()){

            $products = Product::with('category')->get();
            return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('product_category', fn($row) => $row->category->name)
            ->editColumn('price', fn($row) =>
                'Rp. ' . number_format($row->price, 0, ',', '.')
            )
            ->addColumn('action', function($row){
                return '<a href="javascript:void(0)" class="btn btn-danger deletes" data-id="'.$row->id.'">
                        <i class="fas fa-trash"></i> Delete
                        </a>';
            })
            ->make(true);
        }

        $products = Product::with('category')->get();

        return view('pages.product.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $name = 'create';
        return view('pages.product.products.create-edit', compact('name', 'categories'));
    }

    public function search(Request $request)
    {
        $data = [];

        $search = $request->q;

        if(!empty($search)){
            $query = Category::where('name', 'LIKE', '%'. $search .'%')
            ->limit(10)
            ->get();

            foreach($query as $q){
                $data[] = [
                    "id" => $q->id,
                    "text" => $q->name
                ];
            }
        } else {
            $data = collect();
        }




        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $product)
    {
        $validated = $product->validated();

        DB::beginTransaction();

        try{
            Product::create($validated);

            DB::commit();

            return response()->json([
                'status' => 'OK',
                'message' => 'Product Added Successfully']);
        } catch (\Exception $th){
            DB::rollBack();
            return response()->json(['message' => 'Failed to add product: ' . $th->getMessage()], 500);
        }

    }

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
    public function edit(string $id)
    {
        $name = 'edit';
        $product = Product::where('id', $id)->first();

        return view('pages.product.products.create-edit', compact('name', 'product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $field = $request->field;
        $value = $request->value;

        if (in_array($field, ['price'])) {
            $product->$field = $value;
            $product->save();

            return response()->json(['message' => 'Updated Successfully']);
        }

        return response()->json(['message' => 'Invalid field'], 400);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Product::where('id', $id)->delete();
        return response()->json([
            'status' => 'OK',
            'message' => 'Deleted Successfully']);
    }
}
