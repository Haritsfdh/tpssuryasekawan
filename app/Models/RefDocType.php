<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefDocType extends Model
{
    use HasFactory;
    protected $table = 'tps_ref_doc_types';
    protected $guarded = ['id'];
    
}
