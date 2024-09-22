<?php

namespace App\Contracts;

interface OrderRepositoryInterface
{
    public function saveOrder(array $data);

    public function getDetailByOrderId($id);
}
