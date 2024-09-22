<?php

namespace App\Repositories;

use App\Contracts\OrderRepositoryInterface;
use App\Models\RMBOrder;
use App\Models\OrderIndex;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class RMBOrderRepository implements OrderRepositoryInterface
{
    protected $rmbOrder;

    public function __construct(RMBOrder $rmbOrder)
    {
        $this->rmbOrder = $rmbOrder;
    }

    public function saveOrder(array $data)
    {
        DB::beginTransaction();

        try {
            $order = $this->rmbOrder::create([
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
        return $this->rmbOrder::where('order_id', $order_id)->first();
    }
}
