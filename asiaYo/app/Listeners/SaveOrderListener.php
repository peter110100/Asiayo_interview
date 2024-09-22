<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Factories\OrderRepositoryFactory;

class SaveOrderListener
{
    public function handle(OrderCreated $event)
    {
        $repository = OrderRepositoryFactory::create($event->orderData['currency']);
        $repository->saveOrder($event->orderData);
    }
}