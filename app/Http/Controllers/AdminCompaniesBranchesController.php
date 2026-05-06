<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GlbBranchRequest;
use App\Models\GlbCompany;
use App\Models\GlbBranch;

class AdminCompaniesBranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GlbCompany $company)
    {
        // Query the GlbBranch model to get all branches that belong to the given company
        $items = GlbBranch::where('company_id', $company->id)
                          ->get([
                            'id',
                            'CB_Code as Branch Code',
                            'CB_FullName as Branch Name',
                            'CB_Address as Branch Address',
                            'CB_Phone as Branch Phone',
                            'CB_City as Branch City'
                          ]);
        // return to branch index
        return view('admin.companies.index-branches', compact(['company', 'items']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(GlbCompany $company)
    {
        $branch = new GlbBranch; // Create empty branch
        $disabled = 'false'; // Set disabled false
        // Return to create page
        return view('admin.companies.create-edit-branch', compact(['company', 'branch', 'disabled']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GlbCompany $company, GlbBranchRequest $request)
    {       
        // Create branch
        $branch = $company->branches()->create($request->validated());
        $branch->CB_IsActive = true; // Set active
        $branch->save(); // Save
        // Return to branch index
        return redirect('/administrator/companies/'.$company->id.'/branches')->with('sukses', 'Create Branch Success.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GlbCompany $company, GlbBranch $branch)
    {
        $disabled = 'disabled'; // Disabled form input
        // Return to edit page
        return view('admin.companies.create-edit-branch', compact(['company', 'branch', 'disabled']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(GlbCompany $company, GlbBranch $branch)
    {
        $disabled = 'false'; // Disabled false
        // Return to edit page
        return view('admin.companies.create-edit-branch', compact(['company', 'branch', 'disabled']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GlbBranchRequest $request, GlbCompany $company, GlbBranch $branch)
    {        
        // Update branch from request
        $branch->update($request->validated());
        // Return to edit page
        return redirect('/administrator/companies/'.$company->id.'/branches')->with('sukses', 'Update Branch Success.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GlbCompany $company, $id)
    {
        // Search branch from company and delete
        $company->branches()->findOrFail($id)->delete();
        // Return to branch index
        return redirect('/administrator/companies/'.$company->id.'/branches')->with('sukses', 'Delete Branch Success.');
    }

    public function select2(Request $request)
    {
      $cid = $request->id; // Get request id
      // Find active branch by company id
      $results = GlbBranch::where('CB_IsActive', true)
                         ->where('company_id', $cid)
                         ->get();
      $output = ''; // Set empty output
      // Loop for branch results
      foreach ($results as $key => $result) {
        // Add option for every branch
        $output .= '<option value="'.$result->id.'">'.$result->CB_Code .' - '.$result->CB_FullName.'</option>';
      }
      // return output
      echo $output;
    }

    public function selectBranch(Request $request)
    {
        $data = []; // Set empty data
        // Check if request for all branch or not
        if($request->has('all') && $request->all > 0) // if yes
        {
          // Get all branch from active user
          $brids = auth()->user()->branches->pluck('company_id')->toArray();
        } else {
          // Get all branch from active company
          $brids = activeCompany()->company->pluck('id')->toArray();
        }
        // Check if request has keyword
        if(($request->has('q') && $request->q != '')
            || $request->has('all') && $request->all > 0){
          $search = $request->q; // Set search var
          // Query active branch based on search with company relationship
          $data = GlbBranch::with('company')
                            ->where('CB_IsActive', true)
                            ->whereIn('company_id', $brids)
                            ->where(function($query) use($search){
                              $query->where('CB_FullName','LIKE',"%$search%")
                                    ->orWhere('CB_Code','LIKE',"%$search%");
                            })
                            ->limit(10)
                            ->get();
      }
      // return json of $data
      return response()->json($data);
    }
    public function currentBranch(Request $request)
    {
        $data = []; // Set data variable
        $company = activeCompany()->company; // Get company from active user
        // Check if request has keyword
        if($request->has('q') && $request->q != ''){
            $search = $request->q; // Set search variable
            // Query branch based on active user and search
            $data = GlbBranch::with('company')
                              ->where('company_id', $company->id)
                              ->where('CB_IsActive', true)
                              ->where(function($query) use($search){
                                $query->where('CB_FullName','LIKE',"%$search%")
                                      ->orWhere('CB_Code','LIKE',"%$search%");
                              })
                              ->limit(10)
                              ->get();
        }
        // return json $data
        return response()->json($data);
    }
}
