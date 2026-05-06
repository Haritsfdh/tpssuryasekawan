<?php

namespace App\Http\Controllers;

use App\Models\PassLog;
use App\Models\UserLoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Crypt, Auth, Str, DB;

class AuthController extends Controller
{
    public function index()
    {
      if (Auth::check()) {
        //If Authenticated redirect to Dashboard
        return redirect('/dashboard');
      }
      //Return to Welcome page
      return view('pages.welcome');

    }

    public function postlogin(Request $request)
    {
        //Get Request Parameters
        $username = $request->email;
        $password = base64_decode($request->password);

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
          //If Username is Email
          Auth::attempt(['email' => $username, 'password' => $password]);
        } else {
          //If Username is Not Email
          Auth::attempt(['username' => $username, 'password' => $password]);
        }

        if (Auth::check()) {
          // Get Auth User
          $user = Auth::user()->load(['branches']);

          // Check for user branch
          if ($user->branches->isEmpty()) {
            // Logout User
            Auth::logout();
            // Redirect to login page with error message
            return redirect('/')->with('gagal', 'You dont have a branch.');
          }
          // Begin DB Transaction
          DB::beginTransaction();

          try {
            // Create Login logs
            $user->loginLogs()->create(['type' => 'Login']);
            // Save to DB
            DB::commit();
          } catch (\Throwable $th) {
            // Rollback Action
            DB::rollback();
          }

          $user->createToken('login')->plainTextToken;

          // Update timestamps
          $user->touch();
          // Save intended url
          $intended = redirect()->intended('/dashboard')->getTargetUrl();

          // Check Branch
          if ($user->branches->count() > 1) {
            // Redirect to select branch
            return redirect('/active-company')->with('intended', $intended);
          } else {
            // If user have only one branch, get branch
            $branch = $user->branches->first()->id;
            // Auto set active branch for user with only one branch
            $user->branches()->updateExistingPivot($branch, ['active' => true]);
            // Set branch session
            session(['brid' => $branch]);
          }

          //Return to Intended URL
          return redirect()->intended('/dashboard');

        }

        //Return to login page with Errors
        return redirect('/')->with('gagal', 'Your Credential not found.');

    }

    public function profile()
    {
        $user = Auth::user()->load(['roles']);

        if ($user->hasRole('super-admin')) {
          $roles = Role::all(); // Get all role if super-admin
        } else {
          $roles = Role::where('name', '<>', 'super-admin')->get(); //Get role not super-admin
        }
        return view('pages.profile', compact(['user', 'roles']));
    }

    public function update(Request $request, User $user)
    {
        //Validate request
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
        //Redirect url for super-admin or users
        ($user->hasRole('super-admin')) ? $redirBack = '/administrator/profile' : $redirBack = '/profile';
        //If Fail validation
        if ($validator->fails()) {
          return redirect($redirBack)->withErrors($validator);
        }
        //If user not login user
        if ($user->id != Auth::id()) {
          return redirect($redirBack)->with('gagal', 'You are not Authorize to Edit this Account.');
        }
        //If cannot bypass Password check password log
        if ($user->cannot('bypass-password')) {
          if ($request->password != '') {
            //Find 5 Latest Password
            $pass = $request->password;
            $used = 0;

            $check = PassLog::where('user_id', $user->id)
                            ->latest()
                            ->take(5)
                            ->get();

            foreach ($check as $cek) {
              $passCheck = Crypt::decrypt($cek->pass);
              if ($passCheck == $pass) {
                $used++;
              }
            }
            //If password is used in 5 last password changes
            if ($used > 0) {
              return redirect($redirBack)->with('gagal', 'Please insert Password that is not already used.');
            }
          }
        }

        DB::beginTransaction();

        try {
          //Update user data
          $user->update([
            'email' => $request->email,
            'username' => $request->username,
            'name' => Crypt::encrypt($request->name),
          ]);
          //Change password if request password is not empty
          if ($request->password != '') {
            $user->update(['password' => bcrypt($request->password)]);

            //Create Password change logs
            $user->passLog()->create([
              'pass' => Crypt::encrypt($request->password)
            ]);
          }
          //Check if has avatar file
          if ($request->hasFile('avatar')) {
            $ext = $request->file('avatar')->getClientOriginalExtension();
            //Check forbidden file extensions
            if (in_array(Str::lower($ext), getRestrictedExt())) {
              return "FORBIDDEN";
            }

            $fileLama = public_path().'/img/users/'.$user->avatar; // Old file for updating
            //If has old avatar remove file
            if (!is_dir($fileLama) && file_exists($fileLama)) {
              unlink($fileLama);
            }
            //Save file to public folder and update user data
            $name = Str::slug($user->name).'_'.round(microtime(true)).'.'.$ext;
            $request->file('avatar')->move('img/users/', $name);
            $user->update(['avatar' => $name]);
          }

          DB::commit();
          // Redirect to Profile page
          return redirect($redirBack)->with('sukses', 'Edit Profile Success');

        } catch (\Throwable $th) {
          // Rollbach Changes
          DB::rollback();
          // Throw Exception
          throw $th;
        }
    }

    public function logs()
    {
        // Get Auth User
        $uid = Auth::id();
        // Query log login user
        $query = UserLoginLog::where('user_id', $uid)->latest('created_at');
        // Return Datatables
        return DataTables::eloquent($query)
                         ->addIndexColumn() // Add Index Colum
                         ->editColumn('created_at', function($row){ // Edit Column Created
                            // Set Default Variable
                            $display = "-";
                            $timestamp = 0;
                            // Get Created At data
                            $created = $row->created_at;
                            // Chech if created exists
                            if ($created) {
                              // Parse Created at to Display and Timestamp
                              $display = $created->format('d/M/Y H:i:s');
                              $timestamp = $created->timestamp;
                            }
                            // Set Show to return
                            $show = [
                              'display' => $display,
                              'timestamp' => $timestamp
                            ];
                            // Return Show
                            return $show;
                         })
                         ->toJson(); // Return as Json
    }

    public function logout()
    {
        // Get Login User
        $user = Auth::user();
        // DB Transactions
        DB::beginTransaction();

        try {
          // Add logs user logout
          $user->loginLogs()->create(['type' => 'Logout']);
          // Commit Changes
          DB::commit();
        } catch (\Throwable $th) {
          // Rollback Changes
          DB::rollback();
        }
        // Logout User
        Auth::logout();
        // Redirect to Login page
        return redirect('/')->with('sukses', 'Logout success.');
    }
}
