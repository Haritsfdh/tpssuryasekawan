<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GlbCompanyRequest;
use App\Models\GlbCompany;
use App\Models\AccGlAccount;
use Str, DB;

class AdminCompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Query all GlbCompany
        $items = GlbCompany::get([
                             'id',
                             'GC_IsActive',
                             'GC_Name as Company Name',
                             'GC_Address1 as Address',
                             'GC_RN_NKCountryCode as Country',
                             'GC_City as City'
                           ]);
        // return view index
        return view('admin.companies.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = new GlbCompany; // Create empty company
        $disabled = 'false'; // Set disabled to false

        return view('admin.companies.create-edit', compact(['company', 'disabled']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GlbCompanyRequest $request)
    {        
        DB::beginTransaction();
        try {
          // Create GlbCompany based on request
          $company = GlbCompany::create($request->validated());
          $company->update(['GC_IsActive' => true]); // Set active          
          // Check if user upload logo
          if($request->hasFile('GC_Logo')){
            // Get file extension
            $ext = $request->file('GC_Logo')->getClientOriginalExtension();
            // Set file name, upload and set logo
            $name = Str::slug($company->GC_Code).'_'.round(microtime(true)).'.'.$ext;
            $request->file('GC_Logo')->move('img/companies/', $name); // Save logo
            $company->update(['GC_Logo' => $name]); // Update logo
          }
          // Commit changes
          DB::commit(); 
          // return to company index
          return redirect('/administrator/companies')->with('sukses', 'Create Company Success');
        } catch (\Throwable $th) {
          // Rollback transaction and throw exception
          DB::rollback();
          throw $th;
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GlbCompany $company)
    {
        $disabled = 'disabled'; // Set disabled false
        // return view
        return view('admin.companies.create-edit', compact(['company', 'disabled']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(GlbCompany $company)
    {
        $disabled = 'false'; // Set disabled false
        // return view
        return view('admin.companies.create-edit', compact(['company', 'disabled']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GlbCompanyRequest $request, GlbCompany $company)
    {
        DB::beginTransaction();
        try {
          // Update company
          $company->update($request->validated());
          // Check if user upload logo
          if($request->hasFile('GC_Logo')){
            // Get file extension
            $ext = $request->file('GC_Logo')->getClientOriginalExtension();
            // Get current logo file
            $fileLama = public_path().'/img/companies/'.$company->GC_Logo;
            // Check if file exists
            if(!is_dir($fileLama) && file_exists($fileLama)){
              unlink($fileLama); // Remove file
            }
            // Set file name, upload and set logo
            $name = Str::slug($company->GC_Code).'_'.round(microtime(true)).'.'.$ext;
            $request->file('GC_Logo')->move('img/companies/', $name);
            $company->update(['GC_Logo' => $name]); // Update logo
          }
          // Commit changes
          DB::commit(); 
          // return to company index
          return redirect('/administrator/companies/'.$company->id.'/edit')->with('sukses', 'Update Company Success');
        } catch (\Throwable $th) {
          //throw $th;
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GlbCompany $company)
    {
        $company->delete(); // Delete company
        // return view company index
        return redirect('/administrator/companies')->with('sukses', 'Delete Company Success');
    }

    public function select2accounting(Request $request)
    {
      $data = [];

        if($request->has('q') && $request->q != ''){
            $search = $request->q;
            $query = AccGlAccount::select("id","AG_AccountNum","AG_Description")
                                ->where('AG_IsActive', true)
                                ->where(function($query) use($search){
                                  $query->where('AG_AccountNum','LIKE',"%$search%")
                                        ->orWhere('AG_Description','LIKE',"%$search%");
                                });
            if(!$request->has('c')){
              $query->where('AG_ControlAccount', true);
            }
            $data = $query->get();
        }

        return response()->json($data);
    }

    public function select2(Request $request)
    {
        $data = [];

          if($request->has('q') && $request->q != ''){
              $search = $request->q;
              $data = GlbCompany::select("id","GC_Name")
                                  ->where('GC_IsActive', true)
                                  ->where('GC_Name','LIKE',"%$search%")
                                  ->get();
          }

          return response()->json($data);
    }
    
}
