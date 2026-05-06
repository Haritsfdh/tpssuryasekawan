<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlbCompany;

class AdminCompaniesUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GlbCompany $company)
    {
        $company->load('users'); // Load users
        // return view
        return view('admin.companies.users', compact('company'));
    }

    public function update(Request $request, GlbCompany $company)
    {
        //
    }
    
    public function select()
    {
        // Load user by auth and branch company relation
        $user = \Auth::user()->load(['branches.company']);
        // Set branch from user branches
        $branches = $user->branches;
        // return view
        return view('pages.company',compact(['user', 'branches']));
    }

    public function set(Request $request)
    {
        // Set intende var from request
        $intended = $request->intended ?? '/dashboard';
        // Get user auth
        $user = \Auth::user();
        // Set current active branch to non active for selected user
        $user->branches()
              ->newPivotStatement()
              ->where('user_id', '=', $user->id)
              ->update(array('active' => 0));
        // Save user
        $user->save();
        // Save branches user active
        $user->branches()->updateExistingPivot($request->branch_id,
          ['active' => true,]
        );
        // forget session brid
        $request->session()->forget('brid');
        // Set session user branch
        session(['brid' => $request->branch_id]);
        // Redirect to intended url
        return redirect($intended);
    }
}
