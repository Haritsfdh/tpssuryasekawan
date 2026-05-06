<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefPnlDetails extends Model
{
    use HasFactory;

    protected $table = 'tps_ref_pnl_details';
    protected $guarded = ['id'];

    public function pnl()
    {
      return $this->belongsTo(RefPnl::class, 'pnl_id');
    }

    public function glaccount()
    {
      return $this->belongsTo(AccGlAccount::class, 'account_id');
    }
}
