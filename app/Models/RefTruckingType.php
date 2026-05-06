<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefTruckingType extends Model
{
    use HasFactory;
    protected $table = 'tps_ref_trucking_types';
    protected $guarded = ['id'];
    
    public function trucking()
    {
      return $this->belongsTo(RefTrucking::class, 'trucking_id');  
    }

    public function getTypeParseAttribute()
    {
      $trType = $this->getTruckType();
      
      return $trType[$this->type];
    }

    public function getTruckType()
    {
      return collect([
        'mtc' => 'Motor Cycle',
        'blv' => 'Blind Van',
        'cdd' => 'CDD',
        'cde' => 'CDE',
        'wbx' => 'Wing Box',        
        'fso' => 'Fuso',
        'rft' => 'Reefer Truck'
      ]);
    }
}
