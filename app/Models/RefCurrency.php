<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefCurrency extends Model
{
    use HasFactory;
    protected $table = 'ref_currencies';
    protected $guarded = ['id'];

    public function country()
    {
      return $this->hasOne(RefCountry::class, 'RN_RX_NKLocalCurrency', 'RX_Code');
    }

    public function exchangerate()
    {
      return $this->hasMany(RefExchangeRate::class, 'RE_RX_NKExCurrency', 'RX_Code');
    }

    public function exchangeratetoday()
    {
      return $this->exchangerate()->where('start_date', '<=', today()->format('Y-m-d'))
                                  ->where('end_date', '>=', today()->format('Y-m-d'));
    }
}
