<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\OrderRepositoryInterface;
use App\Repositories\TWDOrderRepository;
use App\Repositories\USDOrderRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OrderRepositoryInterface::class, function ($app) {
            if (config('app.default_currency') == 'TWD') {
                return new TWDOrderRepository();
            } else {
                return new USDOrderRepository();
            }
        });
    }

    public function boot()
    {
        //
    }
}
