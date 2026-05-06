<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgAddress extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tps_org_address';
    protected $guarded = ['id'];
    protected $appends = ['full_address'];

    public function header()
    {
      return $this->belongsTo(OrgHeader::class, 'OA_OH');
    }

    public function country()
    {
      return $this->belongsTo(RefCountry::class, 'OA_RN_NKCountryCode','RN_Code');
    }

    // public function contact()
    // {
    //   return $this->hasMany(OrgContact::class, 'id', 'OC_OA_OrgAddress');
    // }

    // public function jobBillingAddressCreditor()
    // {
    //     return $this->hasMany(ShipmentsJobBilling::class, 'JR_OH_CostAccount');
    // }

    // public function jobBillingAddressDebtor()
    // {
    //     return $this->hasMany(ShipmentsJobBilling::class, 'JR_OH_SellAccount');
    // }

    // public function jobBillingAddressCreditorConCost()
    // {
    //     return $this->hasMany(ConsolCosting::class, 'E6_OH_Creditor');
    // }

    public function getFullAddressAttribute()
    {
      return $this->OA_Address1.' '.$this->OA_Address2.' '.$this->OA_City.' '.$this->OA_State.' '.$this->OA_PostCode.' '.$this->country?->RN_Desc;
    }

    public function fullAddress()
    {
      $address = $this->OA_Address1.' '.$this->OA_Address2.' '.$this->OA_City.' '.$this->OA_State.' '.$this->OA_PostCode.' '.$this->country?->RN_Desc;

      return $address; //tadi lupa return
    }

    public function getFakturAddressAttribute()
    {
      return $this->OA_Address1.' '.$this->OA_Address2.' '.$this->OA_City.' '.$this->OA_State.' '.$this->OA_PostCode;
    }

    public function getAddressOverrideAttribute()
    {
      $address = (!$this->OA_CompanyNameOverride) ? $this->header->OH_FullName
                                                  : $this->OA_CompanyNameOverride;

      return $address;
    }
}
