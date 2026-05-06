<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefFreightItems extends Model
{
    use HasFactory;

    protected $table = 'tps_ref_freight_items';
    protected $guarded = ['id'];

    public function refFreight()
    {
      return $this->belongsTo(RefFreight::class, 'freight_id')->withDefault();  
    }

    public function container()
    {
      return $this->belongsTo(RefContainer::class, 'container_id')
                  ->withTrashed()->withDefault();  
    }
}
