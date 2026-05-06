<?php

namespace App\Models;

use App\Models\Sales;
use App\Models\Category;
use App\Models\SalesDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = ['name', 'category_id', 'price'];
    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

}
