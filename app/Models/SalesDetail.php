<?php

namespace App\Models;

use App\Models\Sales;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    protected $fillable = [
    'sales_id',
    'product_id',
    'quantity',
    'price',
    'total_price',
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
