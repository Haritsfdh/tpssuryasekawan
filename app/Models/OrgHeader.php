<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgHeader extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tps_org_header';
    protected $guarded = ['id'];

    public function createdby()
    {
      return $this->belongsTo(User::class, 'OH_SystemCreateUser')->withTrashed();
    }

    public function sales()
    {
      return $this->belongsTo(User::class, 'OH_SalesRep')->withTrashed();
    }
    // public function unloco()
    // {
    //   return $this->belongsTo(Unloco::class, 'OH_RL_NKClosestPort');
    // }

    public function address()
    {
      return $this->hasMany(OrgAddress::class, 'OA_OH', 'id');
    }

    public function mainAddress()
    {
      return $this->address()->where('OA_IsActive', true)->where('OA_Type', 'OFC');
    }

    public function taxAddress()
    {
      return $this->address()->where('OA_IsActive', true)->where('OA_Type', 'TAX');
    }

    public function hasNpwp()
    {
      if($this->taxAddress()->whereNotNull('OA_TaxID')->exists()){
        return true;
      }
      return false;
    }

    // public function companyData()
    // {
    //   return $this->hasMany(OrgCompanyData::class, 'OB_OH', 'id');
    // }

    public function isReceivable()
    {
      if($this->companyData()->where('OB_IsDebtor', true)->exists()){
        return true;
      }

      return false;
    }

    public function isPayable()
    {
      if($this->companyData()->where('OB_IsCreditor', true)->exists()){
        return true;
      }

      return false;
    }

    // public function arSettlementOrg()
    // {
    //   return $this->hasMany(OrgCompanyData::class, 'OB_OH_ARSettlementOrg', 'id');
    // }

    // public function apSettlementOrg()
    // {
    //   return $this->hasMany(OrgCompanyData::class, 'OB_OH_APSettlementOrg', 'id');
    // }

    public function hasArWitholding()
    {
      if($this->companyData()->where('OB_ARWitholdingTax', true)->exists()){
        return true;
      }
      return false;
    }

    public function hasApWitholding()
    {
      if($this->companyData()->where('OB_APWitholdingTax', true)->exists()){
        return true;
      }
      return false;
    }

    public function arCreditTerms()
    {
      return $this->companyData()->whereNotNull('OB_ARInvoiceTermDays')->first()->OB_ARInvoiceTermDays ?? NULL;
    }

    public function apCreditTerms()
    {
      return $this->companyData()->whereNotNull('OB_APPaymentTermDays')->first()->OB_APPaymentTermDays ?? NULL;
    }

    public function isSelfBill()
    {
        return $this->companyData()->where('OB_APCostsSelfBilled',true)->first();
    }

    public function rateSource()
    {
      $arSource = $this->companyData()->where('OB_IsDebtor', true)->first();
      if(!$arSource){
        return "MID";
      }
      return $arSource->OB_RateSource ?? "MID";
    }

    // public function contacts()
    // {
    //   return $this->hasMany(OrgContact::class, 'OC_OH', 'id');
    // }

    // public function edocs()
    // {
    //   return $this->hasMany(FileEdocs::class, 'organization_id', 'id');
    // }

    // public function shipments_shipper(){
    //     return $this->hasOne(Shipments::class, 'JS_ConsignorCode', 'OH_Code');
    // }
    // public function shipments_consignee(){
    //     return $this->hasOne(Shipments::class, 'JS_ConsigneeCode', 'OH_Code');
    // }
    // public function shipments_localclient(){
    //     return $this->hasOne(Shipments::class, 'JS_LocalClientCode', 'OH_Code');
    // }
    // public function shipments_notify(){
    //     return $this->hasOne(Shipments::class, 'JS_NotifyCode', 'OH_Code');
    // }

    // public function jobBillingCreditor()
    // {
    //     return $this->hasMany(ShipmentsJobBilling::class, 'JR_OH_CostAccount');
    // }

    // public function jobBillingDebtor()
    // {
    //     return $this->hasMany(ShipmentsJobBilling::class, 'JR_OH_SellAccount');
    // }

    // public function communications()
    // {
    //   return $this->hasMany(AccCommunication::class, 'ACM_OH');
    // }

    // public function transactions()
    // {
    //   return $this->hasMany(AccTransaction::class, 'AH_OH');
    // }

    public function hasArOutstanding()
    {
      return $this->transactions->whereIn('AH_Ledger', ['AR', 'CB'])
                                ->where('AH_IsPosted', true)
                                ->where('AH_OutstandingAmount', '<>', 0)->count();
    }

    public function hasApOutstanding()
    {
      return $this->transactions->whereIn('AH_Ledger', ['AP', 'CB'])
                                ->where('AH_IsPosted', true)
                                ->where('AH_OutstandingAmount', '<>', 0)->count();
    }

    public function region()
    {
      return $this->address()->first()->OA_RN_NKCountryCode;
    }

    // public function getNpwpAttribute()
    // {
    //   $npwp = $this->taxAddress()->first()->OA_TaxID ?? "-";
    //   return \Str::replace('_', '', $npwp);
    // }

    public function getNameParseAttribute()
    {
      return ($this->OH_LegacyCode ?? $this->OH_Code) . ' - ' . $this->OH_FullName;
    }
}
