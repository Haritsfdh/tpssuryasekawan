<?php

namespace App\Http\Controllers;

use App\Models\House;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ManifestHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if($request->ajax()){
            $query = House::where('MasterID', $request->id);

            $query->with(['print401', 'bclog102']);

            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('X_RAYDATE', function($row){
                return $row->bclog102?->BC_DATE->format('d-M-Y H:i:s') ?? "BELUM XRAY";
            })
            ->addColumn('EstimatedBill', function($row){
                return $row->HEstimatedBM + $row->HEstimatedPPH + $row->HEstimatedPPN;
            })
            ->editColumn('ChargeableWeight', function($row){
                $cw = '<a href="#" class="editcw"
                                        data-pk="'.$row->id.'"
                                        data-url="/api/editcw"
                                        data-name="ChargeableWeight"
                                        data-title="Edit Chargable"
                                        data-placeholder="Chargable Weight"
                                        value="'.($row->ChargeableWeight ?? 0).'">'.($row->ChargeableWeight ?? 0).'</a>';

                return $cw;
            })
            ->addColumn('actions', function($row){

                $id = Crypt::encrypt($row->id);
                $btn = '';

                $btn .= '<button
                        class="btn btn-warning edit btn-xs mr-1 elevation-2"
                        data-target="collapseHouse"
                        data-id="'.$id.'"
                        title="Edit"
                        data-toggle="tooltip">
                        <i class="fas fa-edit"></i></button>';

                $btn .= '<button class="btn btn-info elevation-2 codes btn-xs mr-1"
                        data-target="collapseHSCode
                        data-id="'.$row->id.'"
                        data-code="'.$row->NO_HOUSE_BLAWB.'"
                        data-house="'.$id.'"
                        title="HS Code"
                        data-toggle="tooltip">
                        <i class="fas fa-clipboard"></i></button>';

                $btn .= '<button type="button"
                                          class="btn btn-xs btn-success dropdown-toggle dropdown-icon"
                                          data-toggle="dropdown">
                                            <i class="fa fa-print"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                          <a class="dropdown-item cdr" href="#"
                                            id="btnPrintCargoDeliveryReceipt"
                                            data-id="'.$row->id.'"
                                            data-toggle="modal"
                                            data-target="#modal-PrintCargoDeliveryReceipt">
                                            Cargo Delivery Receipt</a>
                                          <a href="'.route('download.manifest.label', ['house' => $id]).'"
                                            class="dropdown-item"
                                            target="_blank">Label</a>';

                return $btn;
            })
            ->rawColumns(['actions', 'ChargeableWeight'])
            ->toJson();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(House $house)
    {
        $house->load(['unlocoOrigin', 'unlocoTransit', 'unlocoDestination', 'unlocoBongkar']);

        dump($house);

        return response()->json($house);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(House $house)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, House $house)
    {
        $data = $this->validatedHouse();

        if ($data) {

        DB::beginTransaction();

        try {

            $house->update($data);

            $changes = $house->getChanges();

            if(!empty($changes)){
              $info = 'Update House '.($house->NO_BARANG ?? $house->mawb_parse).' <br> <ul>';

              createLog('App\Models\House', $house->id, $info);
              }
            else {
                createLog('App\Models\House', $house->id, 'Update House');
            }

            DB::commit();

            return response()->json([
                'status' => 'OK',
                'message' => 'Update Houses success'
            ]);


            // return redirect('/manifest/consolidation/' . Crypt::encrypt($master->id) . '/edit')
            //     ->with('sukses', 'Create Consolidation success.');

        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return response()->json([
                'status' => 'ERROR',
                'message' => $th->getMessage()
            ]);
        }
    }
    }

    public function updateajax(Request $request)
    {
      $house = House::findOrFail($request->pk);
      $column = $request->name ?? '';

      if($column != '')
      {
        DB::beginTransaction();

        try {
          $house->update([
            $column => $request->value
          ]);

          DB::commit();

          return response()->json([
            'status' => 'OK',
            'message' => 'Update '.$column.' Success.',
            'value' => $request->value
          ]);
        } catch (\Throwable $th) {
          DB::rollback();
          return response()->json([
            'status' => 'ERROR',
            'message' => $th->getMessage()
          ]);
          //throw $th;
        }
      }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(House $house)
    {
        //
    }

    public function validatedHouse()
    {
      return request()->validate([
        'SKIP' => 'required',
        'JNS_AJU' => 'required',
        'KD_DOC' => 'required|numeric',
        'KD_JNS_PIBK' => 'required|numeric',
        'ShipmentNumber' => 'nullable',
        'SPPBNumber' => 'nullable',
        'SPPBDate' => 'nullable|date',
        'BCF15_Status' => 'nullable',
        'BCF15_Number' => 'nullable',
        'BCF15_Date' => 'nullable|date',
        'NO_DAFTAR_PABEAN' => 'nullable',
        'TGL_DAFTAR_PABEAN' => 'nullable|date',
        'SEAL_NO' => 'nullable',
        'SEAL_DATE' => 'nullable|date',
        'NO_HOUSE_BLAWB' => 'required',
        'TGL_HOUSE_BLAWB' => 'nullable|date',
        'NM_PENGIRIM' => 'required',
        'AL_PENGIRIM' => 'required',
        'NM_PENERIMA' => 'required',
        'AL_PENERIMA' => 'required',
        'NO_ID_PENERIMA' => 'nullable',
        'JNS_ID_PENERIMA' => 'nullable|numeric',
        'BADAN_USAHA' => 'nullable|numeric',
        'TELP_PENERIMA' => 'nullable',
        'NETTO' => 'nullable|numeric',
        'BRUTO' => 'nullable|numeric',
        'ChargeableWeight' => 'required|numeric',
        'INCO' => 'nullable',
        'FOB' => 'nullable|numeric',
        'FREIGHT' => 'nullable|numeric',
        'VOLUME' => 'nullable|numeric',
        'ASURANSI' => 'nullable|numeric',
        'JML_BRG' => 'nullable|numeric',
        'JNS_KMS' => 'nullable',
        'MARKING' => 'nullable',
        'tariff_id' => 'nullable|numeric',
        'NPWP_BILLING' => 'nullable',
        'NAMA_BILLING' => 'nullable',
        'NO_INVOICE' => 'nullable',
        'CUS_PO' => 'nullable',
        'TGL_INVOICE' => 'nullable|date',
        'TOT_DIBAYAR' => 'nullable|numeric',
        'NDPBM' => 'nullable|numeric'
      ]);
    }

    public function label(Request $request, House $house)
    {
        $house->load(['master']);

        // if($request->has('format') && $request->format == 'xml')
        // {
        //     return $this->generateXML($house);
        // }

        $nobrg = $house->NO_HOUSE_BLAWB;

        $pdf = Pdf::setOption([
            'enable_php' => true,
            'chroot' => public_path()
        ]);

        $pdf->loadView('exports.label', compact(['house']));

        return $pdf->stream();
    }
}
