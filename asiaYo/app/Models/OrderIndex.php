<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderIndex extends Model
{
    protected $table = 'order_index';

    public $timestamps = false;

    protected $fillable = ['order_id', 'currency'];
}
