<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RMBOrder extends Model
{
    protected $table = 'orders_rmb';

    protected $fillable = ['order_id', 'name', 'address', 'price'];
}
