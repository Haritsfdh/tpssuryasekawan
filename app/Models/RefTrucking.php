<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefTrucking extends Model
{
    use HasFactory;
    protected $table = 'tps_ref_truckings';
    protected $guarded = ['id'];
    protected $casts = [
      'start_date' => 'date',
      'end_date' => 'date'
    ];

    public function organization()
    {
      return $this->belongsTo(OrgHeader::class, 'organization_id')
                  ->withTrashed()->withDefault();  
    }

    public function types()
    {
      return $this->hasMany(RefTruckingType::class, 'trucking_id');  
    }
}
