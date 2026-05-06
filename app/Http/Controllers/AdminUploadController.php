<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Excel;

class AdminUploadController extends Controller
{
    public function upload(Request $request)
    {
      // Get Model from request
      $model = $request->model;
      // Check model for import
      switch ($model) {        
        case 'Users':
          // Import Excel
          Excel::import(new UsersImport(), $request->upload);
          //Return to users page
          return redirect('/administrator/users')->with('sukses', 'Upload Success.');
          break;
        default:
          # code...
          break;
      }
    }
}
