<?php

namespace App\Factories;

use App\Contracts\OrderRepositoryInterface;
use App\Repositories\TWDOrderRepository;
use App\Repositories\USDOrderRepository;
use App\Repositories\JPYOrderRepository;
use App\Repositories\RMBOrderRepository;
use App\Repositories\MYROrderRepository;

use App\Models\TWDOrder;
use App\Models\USDOrder;
use App\Models\JPYOrder;
use App\Models\RMBOrder;
use App\Models\MYROrder;

class OrderRepositoryFactory
{
    public static function create(string $currency): OrderRepositoryInterface
    {
        switch ($currency) {
            case 'TWD':
                return new TWDOrderRepository(new TWDOrder());
            case 'USD':
                return new USDOrderRepository(new USDOrder());
            case 'JPY':
                return new JPYOrderRepository(new JPYOrder());
            case 'RMB':
                return new RMBOrderRepository(new RMBOrder());
            case 'MYR':
                return new MYROrderRepository(new MYROrder());
            default:
                throw new \InvalidArgumentException("Unsupported currency: $currency");
        }
    }
}
