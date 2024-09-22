<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\OrderIndexRepository;
use App\Models\OrderIndex;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderIndexRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testGetOrderByOrderIdSuccess()
    {
        $orderIndex = OrderIndex::create([
            'order_id' => 1,
            'currency' => 'TWD'
        ]);

        $orderIndexRepo = new OrderIndexRepository();
        $order = $orderIndexRepo->getOrderByOrderId(1);

        $this->assertNotNull($order);
        $this->assertEquals(1, $order->order_id);
        $this->assertEquals('TWD', $order->currency);
    }

    public function testGetOrderByOrderIdNotFound()
    {
        $orderIndexRepo = new OrderIndexRepository();
        $order = $orderIndexRepo->getOrderByOrderId(999);

        $this->assertNull($order);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
