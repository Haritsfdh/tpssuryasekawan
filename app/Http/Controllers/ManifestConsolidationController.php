<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Master;
use App\Models\KodeDok;
use App\Models\RefUnloco;
use App\Models\RefAirline;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RefCustomsOffice;
use Yajra\DataTables\DataTables;
use App\Models\RefBondedWarehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\ManifestConsolidationRequest;
use App\Models\OrgHeader;

class ManifestConsolidationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $company = activeCompany();
        $pArr = ['201','203','307','305'];
        $rArr = ['401','408','404'];
        if($request->ajax()){
            $br = $company->id;
            $query = Master::where('mBRANCH', $br)
                          ->withCount([
                            'houses as pending' => function($h) use ($pArr){
                              $h->whereIn('BC_CODE', $pArr);
                            }
                          , 'houses as pendingXRAY' => function($h){
                            $h->where('BC_CODE', '501');
                          }, 'houses as released' => function($h) use ($rArr){
                            $h->whereIn('BC_CODE', $rArr);
                          }]);


            return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('MAWBNumber', function($row){
                            $mawb = $row->mawb_parse;

                            $url = route('manifest.consolidation.edit', Crypt::encrypt($row->id));

                            $show = [
                              'url' => $url,
                              'raw' => $mawb
                            ];
                            $show = '<a href="'.$url.'">'.$mawb.'</a>';

                            return $show;
                           })
            ->addColumn('action', function($row){
                return '<a data-href="'.url()->current().'/'.Crypt::encrypt($row->id).'" class="btn btn-danger delete" data-id="'.$row->id.'">
                        <i class="fas fa-trash"></i></a>';
            })
            ->rawColumns(['UploadStatus', 'action','MAWBNumber'])
            ->make(true);
        }
        return view('pages.manifest.consolidation.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $item = new Master;
        $disabled = false;
        $headerHouse = $this->headerHouse();
        $kodeDocs = KodeDok::all();
        return view('pages.manifest.consolidation.create-edit', compact(['item', 'disabled', 'headerHouse', 'kodeDocs']));
    }

    public function search(Request $request)
    {
        $data = [];

        $search = $request->q;

        if(!empty($search)){
            $query = RefCustomsOffice::where(function($query) use($search){
            $query->where('Kdkpbc', 'LIKE', '%'. $search .'%')
            ->orWhere('UrKdkpbc', 'LIKE', '%'. $search .'%')
            ->orWhere('Kota', 'LIKE', '%'. $search .'%');
            })->limit(5)
            ->get();

            foreach($query as $q){
                $data[] = [
                    "id" => $q->Kdkpbc,
                    "text" => $q->Kdkpbc . ' - ' . $q->UrKdkpbc
                ];
            }
        } else {
            $data = collect();
        }

        return response()->json($data);
    }

        public function searchAirline(Request $request)
    {
        $data = [];

        $search = $request->q;

        if(!empty($search)){
            $data = RefAirline::select('id', "RM_TwoCharacterCode", "RM_AirlineName1", "RM_AccountingCode")
            ->where(function($query) use($search){
            $query->where('RM_TwoCharacterCode', 'LIKE', '%'. $search .'%')
            ->orWhere('RM_AirlineName1', 'LIKE', '%'. $search .'%');
            })
            ->limit(10)
            ->get();

        return response()->json($data);
    }
}
    public function searchOrigin(Request $request)
    {
        $data = [];

        $search = $request->q;

        if(!empty($search)){
            $query = RefUnloco::select('id', "RL_Code", "RL_PortName", "RL_RN_NKCountryCode")
            ->where(function($query) use($search){
            $query->where('RL_Code', 'LIKE', '%'. $search .'%')
            ->orWhere('RL_PortName', 'LIKE', '%'. $search .'%');
            })
            ->limit(5)
            ->get();

            foreach($query as $q){
                $data[] = [
                    "id" => $q->RL_Code,
                    "text" => $q->RL_Code . ' - ' . $q->RL_PortName . ' ( ' . $q->RL_RN_NKCountryCode . ' ) '
                ];
            }
        } else {
            $data = collect();
        }

        return response()->json($data);
    }

    public function searchWarehouse(Request $request)
    {
        $data = [];

        $search = $request->q;

        if(!empty($search)){
            $query = RefBondedWarehouse::select('id', "warehouse_code", "company_name")
            ->where(function($query) use($search){
            $query->where('warehouse_code', 'LIKE', '%'. $search .'%')
            ->orWhere('company_name', 'LIKE', '%'. $search .'%');
            })
            ->get();

            foreach($query as $q){
                $data[] = [
                    "id" => $q->warehouse_code,
                    "text" => $q->warehouse_code . ' - ' . $q->company_name
                ];
            }
        } else {
            $data = collect();
        }

        return response()->json($data);
    }

    public function select2docs(Request $request)
    {
        $data = [];

        $search = $request->q;

            $query = KodeDok::select('id', "kode", "uraian")
            ->where(function($query) use($search){
            $query->where('kode', 'LIKE', '%'. $search .'%')
            ->orWhere('uraian', 'LIKE', '%'. $search .'%');
            })
            ->get();

            foreach($query as $q){
                $data[] = [
                    "id" => $q->kode,
                    "text" => $q->kode . ' - ' . $q->uraian
                ];
            }


        return response()->json($data);
    }

    public function select2company(Request $request)
    {
        $data = [];

        $search = $request->q;

        if(!empty($search)){
            $query = OrgHeader::select("OH_Code", "OH_FullName")
            ->where(function($query) use($search){
            $query->where('OH_Code', 'LIKE', '%'. $search .'%')
            ->orWhere('OH_FullName', 'LIKE', '%'. $search .'%');
            })
            ->get();

            foreach($query as $q){
                $data[] = [
                    "id" => $q->OH_Code,
                    "text" => $q->OH_Code . ' - ' . $q->OH_FullName
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
   public function store(ManifestConsolidationRequest $request)
    {
        $masterData = $request->validated();

        if ($masterData) {
            DB::beginTransaction();

            try {
                $master = Master::create($masterData);

                createLog('App\Models\Master', $master->id, 'Create Consolidation ' . $master->mawb_parse);

                if ($master->HAWBCount > 0) {
                    for ($i = 1; $i <= $master->HAWBCount; $i++) {
                        $houseData = $this->getHouse($master, $i);
                        Log::debug('House Data:', ['data' => $houseData]);

                        $house = House::create($houseData);
                        Log::info('House berhasil dibuat dengan ID: ' . $house->id);
                        createLog('App\Models\House', $house->id, 'Create House ' . $house->mawb_parse);
                    }
                }

                DB::commit();

                return response()->json([
                    'status' => 'OK',
                    'message' => 'Create Consolidation success'
                ]);

            } catch (\Throwable $th ) {
                DB::rollBack();

                Log::error('Gagal menyimpan house: ' . $th->getMessage());
                Log::error('Data terakhir:', [
                    'master' => $masterData ?? null,
                    'house' => $houseData ?? null
                ]);

                return response()->json([
                    'status' => 'ERROR',
                    'message' => $th->getMessage()
                ]);
            }
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
    public function edit(Master $consolidation)
    {

        $item = $consolidation->load('houses');

        $disabled = false;

        $headerHouse = $this->headerHouse();

        $kodeDocs = KodeDok::all();
        return view('pages.manifest.consolidation.create-edit', compact(['item', 'disabled', 'headerHouse', 'kodeDocs']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ManifestConsolidationRequest $request, Master $consolidation)
    {

        $data = $request->validated();

        if ($data) {

        DB::beginTransaction();

        try {

            $consolidation->update($data);

            $changes = $consolidation->getChanges();

            if (!empty($changes)) {
                $info = 'Update Consolidation <br><ul>';
                foreach ($changes as $field => $value) {
                    if ($field !== 'updated_at') {
                        $info .= '<li>' . $field . ' updated to ' . (strip_tags($value) ?? 'NULL') . '</li>';
                    }
                }
                $info .= '</ul>';
                createLog('App\Models\Master', $consolidation->id, $info);
            } else {
                createLog('App\Models\Master', $consolidation->id, 'Update Consolidation (no changes detected)');
            }

            DB::commit();

            return response()->json([
                'status' => 'OK',
                'message' => 'Update Consolidation success'
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Master $consolidation)
    {
        DB::beginTransaction();

        try
        {
            $consolidation->delete();

            DB::commit();

            createLog('App\Models\Master', $consolidation->id, 'Update Consolidation ' . $consolidation->mawb_parse);

            DB::commit();

            return redirect()->route('manifest.consolidation')
                           ->with('sukses', 'Delete Consolidations Success');

        } catch (\Throwable $th) {
          DB::rollback();
          throw $th;
        }
    }

    public function headerHouse()
    {
        $data = collect([
            'id' => 'id',
            'actions' => 'Actions',
            'NO_HOUSE_BLAWB' => 'HAWB No',
            'X_RAYDATE' => 'XRAY Date',
            'NO_FLIGHT' => 'Flight No',
            'NO_BC11' => 'BC 1.1',
            'NO_POS_BC11' => 'POS BC 1.1',
            'NO_SUBPOS_BC11' => 'Sub POS BC 1.1',
            'NM_PENERIMA' => 'Consignee',
            'JML_BRG' => 'Total Items',
            'BRUTO' => 'Gross Weight',
            'ChargeableWeight' => 'Chargable',
            'SCAN_IN_DATE' => 'Scan In',
            'TPS_GateInREF' => 'Gate In Ref',
            'SCAN_OUT_DATE' => 'Scan Out',
            'TPS_GateOutREF' => 'Gate Out Ref',
            'KD_VAL' => 'KD_VAL',
            'FOB' => 'FOB',
            'FREIGHT' => 'FREIGHT',
            'ASURANSI' => 'ASURANSI',
            'CIF' => 'CIF',
            'NDPBM' => 'NDPBM',
            'HEstimatedBM' => 'Est. BM',
            'HEstimatedPPN' => 'Est. PPN',
            'HEstimatedPPH' => 'Est. PPH',
            'EstimatedBill' => 'Est. Bill',
            'BC_CODE' => 'KD Response',
            'BC_STATUS' => 'Keterangan',
        ]);

        return $data;
    }

    public function getHouse(Master $master, $count)
    {
      $data = [
        'MasterID' => $master->id,
        'KD_KANTOR' => $master->KPBC,
        'NM_PENGANGKUT' => $master->NM_SARANA_ANGKUT,
        'NO_FLIGHT' => $master->FlightNo,
        'KD_PEL_MUAT' => $master->Origin,
        'KD_PEL_BONGKAR' => $master->Destination,
        'KD_GUDANG' => $master->OriginWarehouse,
        'KD_NEGARA_ASAL' => $master->unlocoOrigin->RL_RN_NKCountryCode,
        'JNS_AJU' => 4,
        'KD_DOC' => 1,
        'NO_BC11' => $master->PUNumber,
        'TGL_BC11' => $master->PUDate,
        'NO_POS_BC11' => $master->POSNumber,
        'NO_SUBPOS_BC11' => str_pad($count, 4, 0, STR_PAD_LEFT),
        'NO_SUBSUBPOS_BC11' => 0000,
        'NO_MASTER_BLAWB' => $master->MAWBNumber,
        'TGL_MASTER_BLAWB' => $master->MAWBDate,
        'KD_NEG_PENGIRIM' => $master->unlocoOrigin->RL_RN_NKCountryCode,
        'NO_ID_PEMBERITAHU' => $master->NPWP,
        'NM_PEMBERITAHU' => $master->NM_PEMBERITAHU,
        'AL_PEMBERITAHU' => $master->branch->CB_Address,
        'TGL_DEP' => $master->DepartureDate,
        'JAM_DEP' => $master->DepartureTime,
        'TGL_TIBA' => $master->ArrivalDate,
        'JAM_TIBA' => $master->ArrivalTime,
        'KD_PEL_TRANSIT' => $master->Transit,
        'KD_PEL_AKHIR' => $master->Destination,
        'BRANCH' => $master->mBRANCH,
        'PART_SHIPMENT' => $master->Partial,
      ];

      return $data;
    }


}
