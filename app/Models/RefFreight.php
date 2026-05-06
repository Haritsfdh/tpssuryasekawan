<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefFreight extends Model
{
    use HasFactory;

    protected $table = 'tps_ref_freights';
    protected $guarded = ['id'];
    protected $casts = [
      'start_date' => 'date',
      'end_date' => 'date'
    ];

    public function organization()
    {
      return $this->belongsTo(OrgHeader::class, 'organization_id')
                  ->withTrashed()
                  ->withDefault();  
    }

    public function fromUnloco()
    {
      return $this->belongsTo(RefUnloco::class, 'from_id')
                  ->withDefault();  
    }

    public function toUnloco()
    {
      return $this->belongsTo(RefUnloco::class, 'to_id')
                  ->withDefault();
    }

    public function details()
    {
      return $this->hasMany(RefFreightItems::class, 'freight_id');  
    }
}
