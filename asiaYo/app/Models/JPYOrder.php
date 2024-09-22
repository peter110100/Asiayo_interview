<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JPYOrder extends Model
{
    protected $table = 'orders_jpy';

    protected $fillable = ['order_id', 'name', 'address', 'price'];
}