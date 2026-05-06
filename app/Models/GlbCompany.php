<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportAttributes\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlbCompany extends Model
{
    use HasFactory;
    protected $table = 'glb_companies';
    protected $guarded = ['id'];

    public function getLogo()
    {
      return (!$this->GC_Logo) ? asset('/img/default-logo-dark.png') : asset('/img/companies/'.$this->GC_Logo);
    }

    public function getTax() : Attribute
    {
      return new Attribute(
          get: fn ($value) => ( ($value) ? \Str::replace(['.', '-', '_'], '', $this->GC_TaxID)
                                         : ''),
          set: fn ($value) => \Str::replace(['.', '-', '_'], '', $value),
      );
    }

    public function branches()
    {
      return $this->hasMany(GlbBranch::class, 'company_id');
    }
}
