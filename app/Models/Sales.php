<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\SalesDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sales extends Model

{
    protected $fillable = ['date', 'total_price', 'grand_total'];

    protected $appends =['grand_total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(SalesDetail::class);
    }

    public function getProductListAttribute()
    {
        return $this->details->map(function($detail){
            return $detail->product->name . '(' . $detail->quantity . ')';
        })->implode(', ');
    }

    public function getGrandTotalAttribute()
    {
        return $this->details->sum('total_price');
    }

}
