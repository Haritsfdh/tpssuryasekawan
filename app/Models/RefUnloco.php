<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefUnloco extends Model
{
    use HasFactory;
    protected $table = 'tps_ref_unloco';
    protected $guarded = ['id'];

    public function country()
    {
      return $this->belongsTo(RefCountry::class, 'RL_RN_NKCountryCode', 'RN_Code');
    }
}
