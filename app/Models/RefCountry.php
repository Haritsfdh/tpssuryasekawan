<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefCountry extends Model
{
    use HasFactory;
    protected $table = 'tps_ref_countries';
    protected $guarded = ['id'];

    public function currency()
    {
      return $this->belongsTo(RefCurrency::class, 'RN_RX_NKLocalCurrency', 'RX_Code');
    }

    public function vessel()
    {
      return $this->hasMany(RefVessel::class, 'RN_Code', 'RV_RN_NKCountryOfReg');
    }
}
