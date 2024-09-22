<?php

namespace App\Repositories;

use App\Contracts\OrderIndexRepositoryInterface;
use App\Models\OrderIndex;

class OrderIndexRepository implements OrderIndexRepositoryInterface
{
    public function getOrderByOrderId($order_id)
    {
        $order = OrderIndex::where('order_id', $order_id)->first();

        return $order; 
    }
}

