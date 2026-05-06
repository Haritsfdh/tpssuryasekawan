<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefPnl extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tps_ref_pnls';
    protected $guarded = ['id'];

    public function glaccounts()
    {
      return $this->belongsToMany(AccGlAccount::class, 'tps_ref_pnl_details', 'pnl_id', 'account_id');
    }
}
