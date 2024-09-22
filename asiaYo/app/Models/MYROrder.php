<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MYROrder extends Model
{
    protected $table = 'orders_myr';

    protected $fillable = ['order_id', 'name', 'address', 'price'];
}
