<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'order_number','customer_id','order_type','status','subtotal','shipping_cost','discount_amount','total_amount','payment_status','payment_method','delivery_address','delivery_phone','delivery_date_required','estimated_delivery','actual_delivery','actual_weight','actual_total','shipping_provider','tracking_number','notes'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
