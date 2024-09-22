<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TWDOrder extends Model
{
    protected $table = 'orders_twd';

    protected $fillable = ['order_id', 'name', 'address', 'price'];
}
