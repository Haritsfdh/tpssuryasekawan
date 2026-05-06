<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefAirline extends Model
{
    use HasFactory;
    protected $table = 'tps_ref_airlines';
    protected $guarded = ['id'];

    public function getLogo()
    {
      return (!$this->RM_Logo) ? asset('/img/default-airline-logo.png') : "data:image/png;base64, ".$this->RM_Logo;
    }

    public function organization()
    {
      return $this->belongsTo(OrgHeader::class, 'RM_OH');
    }
}
