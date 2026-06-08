<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $fillable = ['name','contact_person','phone','email','address','city','supplier_type','status','credit_limit','current_debt'];
}
