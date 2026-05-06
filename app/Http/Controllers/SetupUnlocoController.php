<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RefUnloco;
use App\Exports\SetupExport;
use App\Imports\SetupImport;
use DataTables, Excel, DB;

class SetupUnlocoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
          $data = RefUnloco::where('RL_IsActive', true);

          return DataTables::eloquent($data)
                            ->addIndexColumn()
                            ->editColumn('RL_Code', function($row){
                              $btn = '<a href="'.route('setup.unloco.edit', ['unloco' => $row->id]).'">'.$row->RL_Code.'</a>';

                              return $btn;
                            })
                            ->addColumn('options', function($row){
                              $options = '';
                              if($row->RL_HasAirport == true){
                                $options .= 'Airport;';
                              }
                              if($row->RL_HasSeaport == true){
                                $options .= 'Seaport;';
                              }
                              if($row->RL_HasRail == true){
                                $options .= 'Rail;';
                              }
                              if($row->RL_HasRoad == true){
                                $options .= 'Road;';
                              }
                              if($row->RL_HasPost == true){
                                $options .= 'Post;';
                              }
                              if($row->RL_HasCustomsLodge == true){
                                $options .= 'Customs Lodge;';
                              }
                              if($row->RL_HasUnload == true){
                                $options .= 'Unload;';
                              }
                              if($row->RL_HasStore == true){
                                $options .= 'Store;';
                              }
                              if($row->RL_HasTerminal == true){
                                $options .= 'Terminal;';
                              }
                              if($row->RL_HasDischarge == true){
                                $options .= 'Discharge;';
                              }
                              if($row->RL_HasOutport == true){
                                $options .= 'Outport;';
                              }
                              if($row->RL_HasBorderCrossing == true){
                                $options .= 'Border Crossing;';
                              }
                              return $options;
                            })
                            ->addColumn('actions', function($row){
                              return 'Actions';
                            })
                            ->rawColumns(['RL_Code'])
                            ->make();
        }

        $items = collect([
          'id' => 'id',
          'RL_Code' => 'Code',
          'RL_PortName' => 'Port Name',
          'RL_NameWithDiacriticals' => 'Diacriticals Name',
          'RL_IATA' => 'RL IATA',
          'RL_RN_NKCountryCode' => 'Country Code',
          'RL_IATARegionCode' => 'IATA Region',
          'options' => 'Options',
          'actions' => 'Actions'
        ]);

        return view('pages.setup.unloco.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new RefUnloco;

        return view('pages.setup.unloco.create-edit', compact(['item']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validatedRequest();

        if($data){
          DB::beginTransaction();

          try {
            $unloco = RefUnloco::create($data);

            DB::commit();

            return redirect()->route('setup.unloco.edit', ['unloco' => $unloco->id])
                             ->with('sukses', 'Create Unloco Success.');
          } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
          }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RefUnloco $unloco)
    {
        $item = $unloco;

        return view('pages.setup.unloco.create-edit', compact(['item']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefUnloco $unloco)
    {
        $data = $this->validatedRequest();

        if($data){
          DB::beginTransaction();

          try {
            $unloco->update($data);

            DB::commit();

            return redirect()->route('setup.unloco.edit', ['unloco' => $unloco->id])
                            ->with('sukses', 'Update Unloco Success.');
          } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
          }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validatedRequest()
    {
      return request()->validate([
        'RL_Code' => 'required',
        'RL_IsActive' => 'required',
        'RL_PortName' => 'required',
        'RL_IATA' => 'nullable',
        'RL_RN_NKCountryCode' => 'required',
        'RL_IATARegionCode' => 'nullable',
        'RL_HasAirport' => 'required',
        'RL_HasSeaport' => 'required',
        'RL_HasRail' => 'required',
        'RL_HasRoad' => 'required',
      ]);
    }

    public function download()
    {
      $model = '\App\Models\RefUnloco';
      return Excel::download(new SetupExport($model), 'unloco.xlsx');
    }

    public function upload(Request $request)
    {
        $model = '\App\Models\RefUnloco';
        Excel::import(new SetupImport($model), $request->upload);
          
        return redirect('/setup/unloco')->with('sukses', 'Upload Success.');
    }

    public function select2(Request $request)
    {
        $data = [];

        if($request->has('q') && $request->q != ''){
            $search = $request->q;
            $query = RefUnloco::select("id","RL_Code","RL_PortName", "RL_RN_NKCountryCode")
                              ->where(function($u) use ($search){
                                $u->where('RL_Code','LIKE',"%$search%")
                                  ->orWhere('RL_PortName','LIKE',"%$search%")
                                  ->orWhere('RL_NameWithDiacriticals', 'LIKE',"%$search%");
                              });                               
                                
            if($request->has('provider')){
              if($request->provider != ''){
                $prop = ($request->provider == 'sea') ? 'RL_HasSeaport' : 'RL_HasAirport';
                $query->where($prop, true);
              } else {
                $query->where(function($p){
                  $p->where('RL_HasSeaport', true)
                    ->orWhere('RL_HasAirport', true);
                });
              }
            }

            $data = $query->groupBy('RL_Code')
                          ->limit(10)
                          ->get();
        }

        return response()->json($data);
    }

    public function selectUNLOCODomestic(Request $request)
    {
        $data = [];

        if($request->has('q') && $request->q != ''){
            $search = $request->q;
            $data = RefUnloco::select("id","RL_Code","RL_PortName")
                                ->where('RL_Code','LIKE',"%$search%")
                                ->orWhere('RL_NameWithDiacriticals','LIKE',"%$search%")
                                ->orWhere('RL_PortName','LIKE',"%$search%")
                                ->groupBy('RL_Code')
                                ->limit(10)
                                ->get();
        }

        return response()->json($data);
    }

    public function selectUNLOCOAir(Request $request)
    {
        $data = [];

        if($request->has('q') && $request->q != ''){
            $search = $request->q;
            $data = RefUnloco::select("id","RL_Code","RL_PortName")
                                ->where('RL_HasAirport','=',"1")
                                ->where(function ($query) use ($search){
                                    $query->where('RL_Code','LIKE',"%$search%")
                                          ->orWhere('RL_NameWithDiacriticals','LIKE',"%$search%")
                                          ->orWhere('RL_PortName','LIKE',"%$search%");
                                })
                                ->groupBy('RL_Code')
                                ->limit(10)
                                ->get();
        }

        return response()->json($data);
    }

    public function selectUNLOCOSea(Request $request)
    {
        $data = [];

        if($request->has('q') && $request->q != ''){
            $search = $request->q;
            $data = RefUnloco::select("id","RL_Code","RL_PortName")
                            ->where('RL_HasSeaport','=',"1")
                            ->where(function ($query) use ($search){
                                $query->where('RL_Code','LIKE',"%$search%")
                                    ->orWhere('RL_NameWithDiacriticals','LIKE',"%$search%")
                                    ->orWhere('RL_PortName','LIKE',"%$search%");
                            })
                            ->groupBy('RL_Code')
                            ->limit(10)
                            ->get();
        }

        return response()->json($data);
    }
}
