<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlbBranch extends Model
{
    use HasFactory;
    protected $table = 'tps_glb_branches';
    protected $guarded = ['id'];

    public function getLogo()
    {
      return (!$this->company?->GC_Logo) ? asset('/img/default-logo-dark.png') : asset('/img/companies/'.$this->company?->GC_Logo);
    }

    public function company()
    {
      return $this->belongsTo(GlbCompany::class, 'company_id');
    }

    public function users()
    {
      return $this->belongToMany(User::class, 'tps_branch_user', 'branch_id', 'user_id')->withPivot('active')->withTimeStamps();
    }

}
