<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefTerminalStorage extends Model
{
    use HasFactory;
    protected $table = 'tps_ref_terminal_storages';
    protected $guarded = ['id'];
    protected $casts = [
      'start_date' => 'date',
      'end_date' => 'date'
    ];

    public function organization()
    {
      return $this->belongsTo(OrgHeader::class, 'organization_id');  
    }

    public function warehouse()
    {
      return $this->belongsTo(RefBondedWarehouse::class, 'warehouse_id');  
    }

    public function schema()
    {
      return $this->belongsTo(Tariff::class, 'schema_id')->withTrashed();  
    }
}
