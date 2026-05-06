<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      $uid = \Crypt::decrypt($request->uid);
      $jenis = $request->jenis;
      $hasil = '';
      $today = today()->subDays(7)->startOfDay()->format('Y-m-d H:i:s');

      $query = Notification::where('user_id', $uid);

      if($jenis == 'count'){
        $hasil = $query->where('read', false)
                       ->count();
      } else {
        $results = $query->where(function($n) use ($today){
                           $n->where('created_at', '>=', $today)
                             ->orWhere('read', false);
                         })
                         ->orderBy('created_at', 'desc')
                         ->get();

        if($results->isEmpty()){
          $hasil .= '<div class="dropdown-divider"></div>
          <span href="#" class="dropdown-item dropdown-footer">0 New Notification</span>';
        } else {
          foreach ($results as $key => $result) {
            $urc = ($result->read == false) ? 'text-primary' : '';
            $hasil .= '<div class="dropdown-divider"></div>
                        <a data-href="'.$result->url.'" data-id="'.\Crypt::encrypt($result->id).'" class="dropdown-item notif-link text-wrap" style="cursor:pointer;">
                          <i class="'.$result->icon.' mr-2"></i>'
                          .($result->read == false ? '<b class="'.$urc.'">' : '')
                          .$result->info 
                          .($result->read == false ? '</b>' : '')
                          .'<span class="float-right text-muted text-sm">'
                          .$result->created_at->shortAbsoluteDiffForHumans().'</span>
                        </a>';
          }
        }          
        
      }

      return $hasil;
    }

    public function read(Request $request)
    {
        $uid = \Crypt::decrypt($request->id);

        $notifications = Notification::findOrFail($uid);

        DB::beginTransaction();

        try {

          $notifications->update(['read' => true]);
          DB::commit();

        } catch (\Throwable $th) {

          DB::rollback();
          return response()->json([
            'status' => 'gagal',
            'message' => $th->getMessage()
          ]);

        }
    }
}
