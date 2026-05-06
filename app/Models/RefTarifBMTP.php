<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefTarifBMTP extends Model
{
    use HasFactory;
    protected $table = 'tps_ref_tarif_bmtp';
    protected $guarded = ['id'];

    public function details()
    {
      return $this->hasMany(HouseDetail::class, 'HS_CODE', 'HSCode');  
    }
}
