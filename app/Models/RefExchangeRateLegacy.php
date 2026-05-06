<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefExchangeRateLegacy extends Model
{
    use HasFactory;
    protected $connection = 'tpslama';
    protected $table = 'cm_taxrate';
    protected $guarded = ['TaxRateID'];
    protected $primaryKey = 'TaxRateID';
}
