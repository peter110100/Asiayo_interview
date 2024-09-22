<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Http\Requests\StoreOrderRequest; 
use App\Http\Controllers\Controller;
use App\Repositories\OrderIndexRepository;
use App\Factories\OrderRepositoryFactory;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected $orderIndexRepo;

    public function __construct(OrderIndexRepository $orderIndexRepo)
    {
        $this->orderIndexRepo = $orderIndexRepo;
    }

    public function store(StoreOrderRequest $request)
    {
        $validatedData = $request->validated();

        event(new OrderCreated($validatedData));

        return response()->json(['message' => 'Order is being processed.'], 200);
    }

    public function show($id): JsonResponse
    {
        $order = $this->orderIndexRepo->getOrderByOrderId($id);
    
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }
    
        $orderRepo = OrderRepositoryFactory::create($order->currency);
    
        $currencyDetails = $orderRepo->getDetailByOrderId($order->order_id);
    
        return response()->json([
            'order' => $order,
            'currency_details' => $currencyDetails,
        ], 200);
    }
}