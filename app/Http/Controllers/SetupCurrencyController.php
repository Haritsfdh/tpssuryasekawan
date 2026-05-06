<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RefCurrency;
use App\Exports\SetupExport;
use App\Imports\SetupImport;
use Excel;

class SetupCurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = RefCurrency::where('RX_IsActive', true)
                              ->get([
                                'id',
                                'RX_Code as Currency Code',
                                'RX_Symbol as Currency Symbol',
                                'RX_Desc as Description',
                                'RX_UnitName as Major Unit',
                                'RX_SubUnitName as Minor Unit',
                                'RX_SubUnitRatio as Minor Unit Ratio'
                              ]);

        return view('pages.setup.currency.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currency = new RefCurrency;
        $disabled = 'false';

        return view('pages.setup.currency.create-edit', compact(['currency', 'disabled']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
          'RX_Code' => 'required',
          'RX_Symbol' => 'required',
          'RX_Desc' => 'required',
          'RX_UnitName' => 'required'
        ]);

        if($data){
          $currency = RefCurrency::create($request->all());
          $currency->RX_IsActive = true;
          $currency->save();

          return redirect('/setup/currency/'.$currency->id.'/edit')->with('sukses', 'Add Currency Success');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(RefCurrency $currency)
    {
        $disabled = 'disabled';

        return view('pages.setup.currency.create-edit', compact(['currency', 'disabled']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(RefCurrency $currency)
    {
        $disabled = 'false';

        return view('pages.setup.currency.create-edit', compact(['currency', 'disabled']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefCurrency $currency)
    {
        $data = $request->validate([
          'RX_Code' => 'required',
          'RX_Symbol' => 'required',
          'RX_Desc' => 'required',
          'RX_UnitName' => 'required'
        ]);

        if($data){
          $currency->update($request->all());

          return redirect('/setup/currency/'.$currency->id.'/edit')->with('sukses', 'Edit Currency Success');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RefCurrency $currency)
    {
        $currency->delete();

        return redirect('/setup/currency')->with('sukses', 'Delete Currency Success');
    }

    public function select2(Request $request)
    {        
        $data = [];

        if($request->has('q') && $request->q != ''){
            $search = $request->q;
            $data = RefCurrency::select("id","RX_Code", "RX_Symbol", "RX_Desc")
                                ->where('RX_Code','LIKE',"%$search%")
                                ->orWhere('RX_Symbol','LIKE',"%$search%")
                                ->orWhere('RX_Desc','LIKE',"%$search%")
                                ->get();
        }

        return response()->json($data);
    }

    public function download()
    {
      $model = '\App\Models\RefCurrency';
      return Excel::download(new SetupExport($model), 'currency.xlsx');
    }

    public function upload(Request $request)
    {
        $model = '\App\Models\RefCurrency';
        Excel::import(new SetupImport($model), $request->upload);
          
        return redirect('/setup/currency')->with('sukses', 'Upload Success.');
    }
}
