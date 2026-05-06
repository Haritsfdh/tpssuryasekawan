<?php

namespace App\Imports;

use DB;
use App\Models\User;
use App\Models\GlbBranch;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
          foreach ($rows as $key => $col) {
            if($key > 0 && $col[0] != ''){
              $user = User::firstOrCreate([
                'email' => $col[2]
              ],[
                'name' => \Crypt::encrypt($col[0]),
                'username' => $col[1],
                'password' => bcrypt('LogistikFMS@2024')
              ]);
  
              if($col[3] !== ''){
                $rl = \Str::replace([' ;','; '], ';', $col[3]);
                $roles = explode(';', $rl);

                if( ! \Auth::user()->hasRole('super-admin') && in_array('super-admin', $roles)) {
                  $newRoles = array_diff($roles, ['super-admin']);

                  $roles = $newRoles;
                }

                $user->syncRoles($roles);
              }

              if($col[4] !== '')
              {
                $brs = \Str::replace([' ;','; '], ';', $col[4]);
                $brss = explode(';', $brs);

                $brids = GlbBranch::whereIn('CB_Code', $brss)->pluck('id')->toArray();

                $user->branches()->sync($brids);
              }
            }
          }
          DB::commit();
        } catch (\Throwable $th) {
          DB::rollback();
          throw $th;
        }
        
        
    }
}
