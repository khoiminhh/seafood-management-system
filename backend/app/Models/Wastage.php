<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wastage extends Model
{
    protected $table = 'wastage';
    protected $fillable = ['inventory_id','product_id','quantity','reason','description','recorded_by','recorded_at'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
