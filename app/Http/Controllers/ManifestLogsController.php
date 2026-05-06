<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Master;
use App\Models\TpsLog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ManifestLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */


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
    public function show(Request $request)
    {
      if($request->ajax()){
        $query = TpsLog::with('user')->latest()->take(75)->get();

        return DataTables::of($query)
                         ->addIndexColumn()
                         ->addColumn('user', function($row){
                          return $row->user->name ?? "-";
                         })
                         ->editColumn('created_at', function($row){
                          return $row->created_at->translatedFormat('l, d F Y H:i');
                         })
                         ->rawColumns(['keterangan'])
                         ->toJson();
      }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TpsLog $tpsLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TpsLog $tpsLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TpsLog $tpsLog)
    {
        //
    }
}
