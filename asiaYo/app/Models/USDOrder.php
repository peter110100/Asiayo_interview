<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class USDOrder extends Model
{
    protected $table = 'orders_usd';

    protected $fillable = ['order_id', 'name', 'address', 'price'];
}
