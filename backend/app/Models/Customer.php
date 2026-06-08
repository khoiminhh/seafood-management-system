<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = ['user_id','phone','email','full_name','address','city','loyalty_points','total_spent','total_orders','last_order_date','status','marketing_consent'];
}
