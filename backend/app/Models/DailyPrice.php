<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPrice extends Model
{
    protected $table = 'daily_prices';
    protected $fillable = ['product_id','price','price_date','time_period','reason','created_by'];
}
