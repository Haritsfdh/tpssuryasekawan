<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PassLog;
use Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {        
        // Get Active User and load relations
        $user = Auth::user()->load(['branches', 'passLog']);
        // Check if user have change password
        if($user->passLog->isEmpty()
            && $user->cannot('bypass-password')){
          // Redirect to profile
          return redirect()->route('profile');
        }
        // Return to default page
        return view('pages.default');
    }

    public function search(Request $request)
    {
      $search = $request->input_search;
      $cari = $request->cari ?? 'tps';
      $user = Auth::user();
      $data = [];
      $output = '';

      if($search != ''){        
        if($cari == 'acc') {          
            $transactions = AccTransaction::with(['organization', 'createuser'])
                                          ->where('AH_TransactionNum', 'LIKE', "%$search%")
                                          ->orWhere('AH_TransactionReference', 'LIKE', "%$search%")
                                          ->orWhere('AH_OriginalTransactionNum', 'LIKE', "%$search%")
                                          ->orWhere('AH_ReceiptBatchNo', 'LIKE', "%$search%")
                                          ->limit(5)
                                          ->get();          
            foreach ($transactions as $t) {
              $ledger = $t->AH_Ledger;
              $jenis = $t->AH_TransactionType;
              switch ($ledger) {
                case 'AR':
                  $url = '/accounting/ar-transactions';
                  break;
                case 'AP':
                  $url = '/accounting/ap-transactions';
                  break;
                case 'CB':
                  $url = '/accounting/cashbook-transactions';
                  break;
                case 'JNL':
                  $url = '/accounting/gl-journals';
                  break;
              }
              if($jenis == 'CSL')
              {
                $url = '/accounting/inv-consol';
              }
              $isi = $t->AH_TransactionNum;
              if(in_array($jenis, ['REC', 'PAY', 'DRP', 'DRR'])){
                $isi .= ' - '.$t->AH_OriginalTransactionNum;
                $isi .= ' ( '.$t->AH_ReceiptBatchNo.' )';
              }
              $isi .= ' || ' . $t->AH_Desc;
              $isi .= ' - Ref. ' .($t->AH_TransactionReference ?? "-");

              $isi .= ' || ' . $t->organization?->OH_FullName ?? "-";
              // $isi .= ' - Created By: ' . $t->createuser->name;

              if($t->AH_IsCancelled == true){
                $isi .= ' <span class="text-danger">( Cancelled )</span>';
              }

              $detail = $t->AH_Ledger .' '. $t->AH_TransactionType;
              
              $output .= '<a href="'.$url.'/'.$t->id.'" class="dropdown-item">
                            <div class="row">
                              <div class="col-9 text-wrap">
                                <i class="fas fa-book mr-2"></i>'.$isi.'
                              </div>
                              <div class="col-3">
                                <span class="float-right text-muted text-sm">'.$detail.'</span>
                              </div>
                            </div>
                          </a>';
            }
        }
        if($cari == 'shp') {          
            $shipment = Shipments::where(function($sh) use ($search) {
                                    $sh->where('JS_UniqueConsignRef', 'LIKE', "%$search%")
                                      ->orWhere('JS_HouseBill', 'LIKE', "%$search%")
                                      ->orWhereHas('consignorname', function($cr) use ($search){
                                        return $cr->where('OH_FullName', 'LIKE', "%$search%");
                                      })
                                      ->orWhereHas('consigneename', function($ce) use ($search){
                                        return $ce->where('OH_FullName', 'LIKE', "%$search%");
                                      });
                                  })
                                  ->with(['consignorname', 'consigneename'])
                                  ->where('JS_IsCancelled', false)
                                  ->limit(5)
                                  ->get();
            foreach ($shipment as $s) {
              $dept = $s->JS_GE;
              switch ($dept) {
                case 37:
                  $url = '/export/seafreight/shipments/'.$s->id;
                  if($user->can('edit_export_seafreight_shipments')) {
                    $url .= '/edit';
                  }
                  break;
                case 56:
                  $url = '/import/airfreight/shipments/'.$s->id;
                  if($user->can('edit_import_airfreight_shipments')) {
                    $url .= '/edit';
                  }
                  break;
                case 67:
                  $url = '/import/seafreight/shipments/'.$s->id;
                  if($user->can('edit_import_seafreight_shipments')) {
                    $url .= '/edit';
                  }
                  break;
                case 83:
                  $url = '/export/airfreight/shipments/'.$s->id;
                  if($user->can('edit_export_airfreight_shipments')) {
                    $url .= '/edit';
                  }
                  break;
                default:
                  $url = '/domestic/shipments/'.$s->id;
                  if($user->can('edit_domestic_shipments')) {
                    $url .= '/edit';
                  }
                  break;
              }
              $title = $s->JS_UniqueConsignRef;
              $desc = $s->JS_HouseBill;
              $info = Str::limit($s->JS_GoodsDescription, 150, '...more');
              $cne = 'SHP: ' . $s->consignorname->OH_FullName . '; CNE: ' . $s->consigneename->OH_FullName;
              $detail = $s->JS_RL_NKOrigin .' > '. $s->JS_RL_NKDestination;
              
              $output .= '<a href="'.$url.'" class="dropdown-item">
                            <div class="row">
                              <div class="col-9 text-wrap">
                                <i class="fas fa-book mr-2"></i>'.$title.' - '. $desc .' ( '.$info.' )'.' || ' . $cne .'
                              </div>
                              <div class="col-3">
                                <span class="float-right text-muted text-sm">'.$detail.'</span>
                              </div>
                            </div>
                          </a>';
            }
        }
        if($cari == 'csl') {
          
            $consolidations = ExpAirConsol::where(function($q) use ($search) {
                                            $q->where('JK_UniqueConsignRef', 'LIKE', "%$search%")
                                              ->orWhere('JK_MasterBillNum', 'LIKE', "%$search%");
                                          })
                                          ->where('JK_IsCancelled', false)
                                          ->limit(5)
                                          ->get();
            
            foreach ($consolidations as $c) {
              $dept = $c->JK_GE;
              switch ($dept) {
                case 37:
                  $url = '/export/seafreight/consolidations/'.$c->id;
                  if($user->can('edit_export_seafreight_consolidations')) {
                    $url .= '/edit';
                  }
                  break;
                case 56:
                  $url = '/import/airfreight/consolidations/'.$c->id;
                  if($user->can('edit_import_airfreight_consolidations')) {
                    $url .= '/edit';
                  }
                  break;
                case 67:
                  $url = '/import/seafreight/consolidations/'.$c->id;
                  if($user->can('edit_import_seafreight_consolidations')) {
                    $url .= '/edit';
                  }
                  break;
                case 83:
                  $url = '/export/airfreight/consolidations/'.$c->id;
                  if($user->can('edit_export_airfreight_consolidations')) {
                    $url .= '/edit';
                  }
                  break;
                default:
                  $url = '#';
                  break;
              }
              $title = $c->JK_UniqueConsignRef;
              $desc = $c->JK_MasterBillNum;
              $info = '';
              $detail = $c->JK_RL_NKLoadPort .' > '. $c->JK_RL_NKDischargePort;
              
              $output .= '<a href="'.$url.'" class="dropdown-item">
                            <div class="row">
                              <div class="col-9 text-wrap">
                                <i class="fas fa-book mr-2"></i>'.$title.' - '. $desc .'
                              </div>
                              <div class="col-3">
                                <span class="float-right text-muted text-sm">'.$detail.'</span>
                              </div>
                            </div> 
                          </a>';
            }
        }
        if($cari == 'tph') {
          
            $houses = House::where('NO_BARANG', 'LIKE', "%{$search}%")
                            ->orWhere('ShipmentNumber', 'LIKE', "%{$search}%")
                            ->with('branch')
                            ->select('id', 'MasterID', 'BRANCH', 'NO_BARANG', 'ShipmentNumber','NO_MASTER_BLAWB')
                            ->limit(5)
                            ->get();

            foreach($houses as $house)
            {
              $title = $house->mawb_parse;              
              $branch = $house->branch->CB_Code ?? "-";

              $id = \Crypt::encrypt($house->id);
              $url = '/manifest/shipments/'.$id;

              if($user->can('edit_manifest_shipments'))
              {
                $url .= "/edit";
              }

              $url .= '#main-data-content';

              $desc = $house->NO_BARANG;

              if($house->ShipmentNumber)
              {
                $desc .= ' ( '.$house->ShipmentNumber.' )';
              }
              $detail = 'TPS Shipments - '.$branch;
              
              
              $output .= '<a href="'.$url.'" class="dropdown-item">
                            <div class="row">
                              <div class="col-9 text-wrap">
                                <i class="fas fa-book mr-2"></i>'.$title.' - '. $desc .'
                              </div>
                              <div class="col-3">
                                <span class="float-right text-muted text-sm">'.$detail.'</span>
                              </div>
                            </div> 
                          </a>';
            }
        }
        if($cari == 'tpm') {
          
            $master = Master::where(function($m) use ($search){
                              $mawb = \Str::replace(['-', ' '], '', $search);

                              $m->where('MAWBNumber', 'LIKE', "%{$mawb}%");
                            })
                            ->orWhere('ConsolNumber', 'LIKE', "%{$search}%")
                            ->with('branch')
                            ->select('id', 'MAWBNumber', 'ConsolNumber', 'mBRANCH', 'HAWBCount')
                            // ->withCount('houses')
                            ->limit(5)
                            ->get();

            foreach($master as $m)
            {
              $title = $m->mawb_parse;
              if($m->ConsolNumber) {
                $title .= ' ( '.$m->ConsolNumber.' )';
              }
              $branch = $m->branch->CB_Code ?? "-";
              
              $id = \Crypt::encrypt($m->id);
              $url = "/manifest/consolidations/'.$id.'";

              if($user->can('edit_manifest_consolidations'))
              {
                $url .= "/edit";
              }

              $url .= '#main-data-content';

              $desc = $m->HAWBCount . ' Houses';
              $detail = 'TPS Consolidations - '.$branch;
              
              $output .= '<a href="'.$url.'" class="dropdown-item">
                            <div class="row">
                              <div class="col-9 text-wrap">
                                <i class="fas fa-book mr-2"></i>'.$title.' - '. $desc .'
                              </div>
                              <div class="col-3">
                                <span class="float-right text-muted text-sm">'.$detail.'</span>
                              </div>
                            </div> 
                          </a>';
            }
          // }
        }        
      } else {
        $output .= '<span class="dropdown-item">Empty Results</span>';        
      }      
      echo $output;      
    }

    public function encrypt(Request $request){
      if($request->id){
        $encrypted = \Crypt::encrypt($request->id);

        return response()->json([
          'status' => 'OK',
          'encrypted' => $encrypted
        ]);
      }
    }
}
