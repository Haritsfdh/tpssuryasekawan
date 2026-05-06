<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\GlbCompany;
use App\Models\GlbBranch;
use DataTables;
use Crypt;
use Str;
use DB;

class AdminUsersController extends Controller
{    
    public function index(Request $request)
    {        
        if( $request->ajax() ) { // Get databable if request Ajax
          // Set default Query
          $query = User::with('roles');

          return DataTables::eloquent($query)
                           ->addColumn('cekbox', function($row){ // Add Column Checkbox
                             // Add Chechbox Button 
                             $chk = '<input type="checkbox"
                                            id="chk_'.$row->id.'">';
                             // Return Button
                             return $chk;
                           })
                           ->editColumn('name', function($row){ // Edit Column Name
                            // Add Name as Link
                            $name = '<a href="'.route('admin.users.edit', $row->id).'">'.$row->name.'</a>';
                            // Return Name
                            return $name;
                           })
                           ->editColumn('avatar', function($row){ // Edit Column Avatar
                             // Add Image as user avatar
                             $img = '<img src="'.$row->getAvatar().'"
                                          class="img-fluid img-circle elevation-2"
                                          style="max-height:80px;width:auto;">';
                             // Return Image
                             return $img;
                           })
                           ->editColumn('created_at', function($row){ // Edit Column Created
                             // Change Created At Format locale
                             return $row->created_at->locale('id_ID')
                                        ->format('d-m-Y');
                           })
                           ->editColumn('updated_at', function($row){ // Edit Column Updated
                             // Check if user have login
                             if ($row->created_at == $row->updated_at) {
                               // Info for not login
                               $lastLogin = 'Not Login';
                             } else {
                               // Info for last login
                               $lastLogin = $row->updated_at->diffForHumans();
                             }
                             // Return Login info
                             return $lastLogin;
                           })
                           ->addColumn('roles', function($row){ // Add Column Roles
                             // Get roles from user
                             $roles = $row->roles;
                             // Check if user has roles
                             if ($roles) {
                               // List roles
                               $ro = '';
                               // Loop all user roles
                               foreach ($roles as $role) {
                                 $ro .= '<span class="badge badge-info">'.$role->name.'</span></br>';
                               }
                             } else { // None if user doesnt have role
                               $ro = '<span class="badge badge-danger">None</span>';
                             }
                             // Return Role
                             return $ro;
                           })
                           ->addColumn('actions', function($row){ // Add Column Actions
                            // Crypt id
                            $id = \Crypt::encryptString($row->id);
                            // Add Button View
                            $btn = '<a href="'.url()->current().'/'.$id.'" 
                                       class="btn btn-xs elevation-2 btn-info elevation-2">
                                       <i class="fas fa-eye"></i> View</a> ';
                            // Add Button Edit
                            $btn .= '<a href="'.url()->current().'/'.$id.'/edit" 
                            class="btn btn-xs elevation-2 btn-warning elevation-2">
                            <i class="fas fa-edit"></i> Edit</a> ';
                            // Add Button Delete
                            $btn .= '<a data-href="'.url()->current().'/'.$id.'" 
                            class="btn btn-xs elevation-2 btn-danger elevation-2 delete">
                            <i class="fas fa-trash"></i> Delete</a> ';
                            //Check if deleted
                            if ($row->deleted_at) {
                              // Add Button Restore
                              $btn .= '<a data-href="restore/'.$id.'" 
                              class="btn btn-xs elevation-2 btn-info elevation-2">
                              <i class="fa-trash-restore"></i> Delete</a> ';
                            }                            
                            // Return Button
                            return $btn;
                           })
                           ->rawColumns(['cekbox', 'name', 'avatar', 'roles', 'actions'])
                           ->toJson();
        }
        // Collection of Table Columns
        $items = collect([
          'cekbox' => 'cekbox',
          'name' => 'Name',
          'email' => 'Email',
          'avatar' => 'Avatar',
          'created_at' => 'Created At',
          'updated_at' => 'Last Activity',
          'roles' => 'Roles',
          'actions' => 'Actions'
        ]);
        // Return view
        return view('admin.users.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Set new User as user variable
        $user = new User;
        // Get All roles except super admin
        $roles = Role::where('name', '<>', 'super-admin')->get();
        // Get All Active Branches
        $branches = GlbBranch::with('company')->where('CB_IsActive', true)->get();
        // Return view
        return view('admin.users.create-edit', compact(['user', 'roles', 'branches']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {        
        // Get validated user request
        $data = $request->safe()->except(['role', 'branches', 'avatar']);

        // Begin DB Transaction
        DB::beginTransaction();
        // Try store process
        try {
          // Create or Update user data from email
          $newUser = User::updateOrCreate([
                            'email' => $data['email'],
                          ],[
                            'username' => $data['username'],
                            'name' => Crypt::encryptString($data['name']),
                            'password' => bcrypt($data['password'])
                          ]);          
          // Check if input role is not empty
          if ($request->role != '') {
            // Assign role to user
            $newUser->assignRole($request->role);
          }
          // Check if input branches is not empty
          if ($request->branches != '') {
            // Assign branches to user
            $newUser->branches()->attach($request->branches); 
          }

          // Commit Changes
          DB::commit();

          // Check if avatar field has image
          if($request->hasFile('avatar')){
            // Get File extension
            $ext = $request->file('avatar')->getClientOriginalExtension();
            // Check if file extension is allowed
            if(in_array(Str::lower($ext), getRestrictedExt())){
              // Return back if extension is not allowed
              return redirect()->back()->with('gagal', 'Avatar file extension of '.$ext.' is not allowed');
            }
            // Set file name for avatar
            $name = Str::slug($newUser->name).'_'.round(microtime(true)).'.'.$ext;
            // Save file
            $request->file('avatar')->move('img/users/', $name);
            // Update avatar name in model user
            $newUser->update([
              'avatar' => $name
            ]);
            // Commit Changes
            DB::commit();
          }
          // Return to user index with success message
          return redirect('/administrator/users')->with('sukses', 'Create User Success.');
        } catch (\Throwable $th) {
          // Rollback changes
          DB::rollback();
          // Return to user index with error messages
          return redirect('/administrator/users')->with('gagal', $th->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Load user roles
        $user->load(['roles']);
        // Set roles variable
        $roles = $user->roles;
        // Get all active branches
        $branches = GlbBranch::with('company')->where('CB_IsActive', true)->get();
        // Return view
        return view('admin.users.create-edit', compact(['user', 'roles', 'branches']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Load user roles
        $user->load(['roles']);
        // Add role query
        $roleQuery = Role::query();
        // Check if user has role of super-admin
        if (! $user->hasRole('super-admin')) {
          // Update role query except
          $roleQuery->where('name', '<>', 'super-admin');
        }
        // Get Roles from query
        $roles = $roleQuery->get();
        // Get all active branches
        $branches = GlbBranch::with('company')->where('CB_IsActive', true)->get();
        // Return view
        return view('admin.users.create-edit', compact(['user', 'roles', 'branches']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Validate Request
        $validator = Validator::make($request->all(), [
                                'name' => 'required',
                                'username' => [
                                  'required',
                                  Rule::unique('users')->ignore($user)
                                ],
                                'email' => [
                                  'required',
                                  Rule::unique('users')->ignore($user)
                                ],         
                              ]);
        // If validator fails
        if ($validator->fails()) {
          // Return to user edit with validator
          return redirect('/administrator/users/'.Crypt::encrpytString($user->id).'/edit')->withErrors($validator);
        }
        // Check if user assign super-user role
        if ($request->role != '' 
              && ! auth()->user()->hasRole('super-admin') 
              && in_array('super-admin', $request->role)) {
          return redirect()->back()->with('gagal', 'You are not authorized to assign this role');
        }
        // Begin DB Transaction
        DB::beginTransaction();
        // Try Update Process
        try {
          // Update user data
          $user->update([
                        'email' => $request->email,
                        'username' => $request->username,
                        'name' => Crypt::encryptString($request->name),
                      ]);
          // Check if input password is not empty
          if ($request->password != '') {
            // Update User Password
            $user->update([
              'password' => bcrypt($request->password)
            ]);
          }
          // Check if input roles is not empty
          if ($request->role != '') { // If role is not empty
            // Sync roles
            $user->syncRoles($request->role);
          } else { // If Empty
            // Detach user roles
            $user->detachRoles();
          }
          // Check if input branches is not empty
          if ($request->branches != '') { // If branches is not empty
            // Sync branches
            $user->branches()->sync($request->branches);
          } else { // If empty
            // Detach user branches
            $user->branches()->detach();
          }
          // Commit changes
          DB::commit();
          // Check if input avatar has file
          if ($request->hasFile('avatar')) {
            // Get the original file extension of the uploaded avatar
            $ext = $request->file('avatar')->getClientOriginalExtension();
            // Check if the file extension is in the list of restricted extensions (e.g., exe, js, etc.)
            if (in_array(Str::lower($ext), getRestrictedExt())) {
              // Return back if extension is not allowed
              return redirect()->back()->with('gagal', 'Avatar file extension of '.$ext.' is not allowed');
            }
            // Define the path to the user's existing avatar file
            $fileLama = public_path().'/img/users/'.$user->avatar;
            // If the existing avatar file is not a directory and it exists, delete it
            if (!is_dir($fileLama) && file_exists($fileLama)) {
              unlink($fileLama);
            }
            // Generate a new file name using the user's name and the current microtime
            $name = Str::slug($user->name).'_'.round(microtime(true)).'.'.$ext;
            // Move the new avatar file to the 'img/users/' directory with the new name
            $request->file('avatar')->move('img/users/', $name);
            // Update the user's avatar field with the new file name
            $user->update([
              'avatar' => $name
            ]);
            // Save changes
            DB::commit();
          }
          // Redirect back to the edit user page with a success message
          return redirect('/administrator/users/'.Crypt::encryptString($user->id).'/edit')->with('sukses', 'Update User Success.');
        } catch (\Throwable $th) {
          // Rollback changes
          DB::rollback();
          // Redirect back to the user's edit page with an error message from the exception
          // The user ID is encrypted for security
          return redirect('/administrator/users/'.Crypt::encryptString($user->id).'/edit')->with('gagal', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Check if the user has the 'super-admin' role
        if($user->hasRole('super-admin')){
          // If yes, prevent deletion and redirect back with an error message
          return redirect()->back()->with('gagal', 'You can\'t remove a super admin User.');
        }
        // Detach all roles associated with the user
        $user->roles()->detach();
        // Detach all direct permissions assigned to the user
        $user->permissions()->detach();
        // Detach all branch associations the user may have
        $user->branches()->detach();
        // Delete the user record from the database
        $user->delete();
        // Redirect to user Index with success message
        return redirect('/administrator/users')->with('sukses', 'Delete User Success.');
    }

    public function restore(User $user)
    {
        // Restore user
        $user->restore();
        // Return to edit user with success message
        return redirect()->route('admin.users.edit', ['user' => \Crypt::encryptString($user->id)])
                         ->with('sukses', 'Restore user Success');
    }
}
