<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variant extends Model
{
    protected $table = 'variants';
    protected $fillable = ['product_id','variant_type','variant_value','description','price_adjustment','sku_suffix','status'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
