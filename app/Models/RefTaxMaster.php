<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefTaxMaster extends Model
{
    use HasFactory;

    protected $table = 'tps_ref_tax_master';
    protected $guarded = ['id'];

    public function childrens()
    {
      return $this->hasMany(RefTaxMaster::class, 'TX_ParentID')->where('TX_IsActive', true);
    }

    public function glaccount()
    {
      return $this->belongsTo(AccGlAccount::class, 'TX_AG');
    }

    public function glpayable()
    {
      return $this->belongsTo(AccGlAccount::class, 'TX_AGPayable');
    }

    public function glreceivable()
    {
      return $this->belongsTo(AccGlAccount::class, 'TX_AGReceivable');
    }

    public function codes()
    {
      return $this->hasMany(TaxCode::class, 'TC_AT');
    }

    public function jobBillingTaxCost()
    {
        return $this->hasMany(ShipmentsJobBilling::class, 'JR_AT_CostGSTRate');
    }

    public function jobConsolCostTax()
    {
        return $this->hasMany(ConsolCosting::class, 'E6_AT_TaxRate');
    }

    public function jobBillingTaxRev()
    {
        return $this->hasMany(ShipmentsJobBilling::class, 'JR_AT_SellGSTRate');
    }

    public function OthChgTaxConCost()
    {
        return $this->belongsTo(ConsolJobOtherCharges::class, 'JY_AT_SellGSTRate', 'id');
    }

    public function OthChgTaxShp()
    {
        return $this->belongsTo(ShipmentJobOtherCharges::class, 'JX_AT_SellGSTRate', 'id');
    }
}
