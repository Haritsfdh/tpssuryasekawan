<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\RefCountry;
use App\Exports\SetupExport;
use App\Imports\SetupImport;
use Excel;

class SetupCountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $items = RefCountry::where('RN_IsActive', true)
                           ->get([
                            'id',
                            'RN_Code as Country Code',
                            'RN_Desc as Country Description',
                            'RN_EconomicGrouping as Economic Grouping',
                            'RN_CountryDialingCode as Dialing Code',
                            'RN_AddressFormattingRule as Address Formating Rule',
                            'RN_RX_NKLocalCurrency as Local Currency',
                          ]);

       return view('pages.setup.country.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('super-admin')){
          return "OK";
        }

        return redirect('/setup/countries')->with('gagal', 'Please contact your Administrator to Create a Country');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->hasRole('super-admin')){
          return "OK";
        }
        
        return redirect('/setup/countries')->with('gagal', 'Please contact your Administrator to Create a Country');
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
    public function edit($id)
    {
        if(auth()->user()->hasRole('super-admin')){
          return "OK";
        }
        
        return redirect('/setup/countries')->with('gagal', 'Please contact your Administrator to Edit a Country');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()->hasRole('super-admin')){
          return "OK";
        }
        
        return redirect('/setup/countries')->with('gagal', 'Please contact your Administrator to Update a Country');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->hasRole('super-admin')){
          return "OK";
        }
        
        return redirect('/setup/countries')->with('gagal', 'Please contact your Administrator to Delete a Country');
    }

    public function select2(Request $request)
    {        
        $data = [];

        if($request->has('q') && $request->q != ''){
            $search = $request->q;
            $query = RefCountry::select('id', 'RN_Code', 'RN_Desc');

            if($request->has('precise') && $request->precise = 1){
              $data = $query->where('RN_Code', $search)->first();
            } else {
              $data = $query->where('RN_Code','LIKE',"%$search%")
                            ->orWhere('RN_Desc','LIKE',"%$search%")
                            ->groupBy('RN_Code')
                            ->limit(5)
                            ->get();;
            }            
        }

        return response()->json($data);
    }

    public function download()
    {
      $model = '\App\Models\RefCountry';
      return Excel::download(new SetupExport($model), 'countries.xlsx');
    }

    public function upload(Request $request)
    {
        $model = '\App\Models\RefCountry';
        Excel::import(new SetupImport($model), $request->upload);
          
        return redirect('/setup/countries')->with('sukses', 'Upload Success.');
    }
    
}
