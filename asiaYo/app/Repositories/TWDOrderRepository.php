<?php

namespace App\Repositories;

use App\Contracts\OrderRepositoryInterface;
use App\Models\TWDOrder;
use App\Models\OrderIndex;
use Illuminate\Support\Facades\DB;

class TWDOrderRepository implements OrderRepositoryInterface
{
    protected $twdOrder;

    public function __construct(TWDOrder $twdOrder)
    {
        $this->twdOrder = $twdOrder;
    }

    public function saveOrder(array $data)
    {
        DB::beginTransaction();

        try {
            $order = $this->twdOrder::create([
                'order_id' => $data['id'],
                'name' => $data['name'],
                'address' => json_encode([  
                    'city' => $data['address']['city'],
                    'district' => $data['address']['district'],
                    'street' => $data['address']['street'],
                ]),
                'price' => $data['price'],
                'created_at' => now()
            ]);

            OrderIndex::create([
                'order_id' => $data['id'],
                'currency' => $data['currency']
            ]);

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getDetailByOrderId($order_id)
    {
        return $this->twdOrder::where('order_id', $order_id)->first();
    }
}
