<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name'];
    
    public function product(): HasMany
    {
        return $this->HasMany(Product::class, 'category_id');
    }

    use SoftDeletes;
}
