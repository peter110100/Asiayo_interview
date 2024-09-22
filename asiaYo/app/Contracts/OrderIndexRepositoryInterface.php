<?php

namespace App\Contracts;

interface OrderIndexRepositoryInterface
{
    public function getOrderByOrderId($id);
}