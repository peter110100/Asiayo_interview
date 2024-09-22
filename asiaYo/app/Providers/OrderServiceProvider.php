<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\OrderRepositoryInterface;
use App\Factories\OrderRepositoryFactory;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OrderRepositoryInterface::class, function ($app) {
            $currency = request()->input('currency'); // Assume currency is provided in the request
            return OrderRepositoryFactory::create($currency);
        });
    }
}