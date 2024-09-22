<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\JPYOrderRepository;
use App\Models\JPYOrder;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JPYOrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSaveOrderSuccess()
    {
        $jpyOrderMock = Mockery::mock(JPYOrder::class);

        $jpyOrderMock->shouldReceive('create')
            ->once()
            ->withArgs(function ($args) {
                return $args['order_id'] === 1 &&
                       $args['name'] === 'Test JPY Order' &&
                       json_decode($args['address']) == (object) [
                           'city' => 'Tokyo',
                           'district' => 'Shibuya',
                           'street' => '123 Street'
                       ] &&
                       $args['price'] === 1000;
            })
            ->andReturn((object) [
                'id' => 1,
                'order_id' => 1,
                'name' => 'Test JPY Order',
                'address' => '{"city":"Tokyo","district":"Shibuya","street":"123 Street"}',
                'price' => 1000,
                'created_at' => now()
            ]);

        $orderRepository = new JPYOrderRepository($jpyOrderMock);

        $data = [
            'id' => 1,
            'name' => 'Test JPY Order',
            'address' => [
                'city' => 'Tokyo',
                'district' => 'Shibuya',
                'street' => '123 Street'
            ],
            'price' => 1000,
            'currency' => 'JPY'
        ];

        $order = $orderRepository->saveOrder($data);

        $this->assertEquals(1, $order->id);
        $this->assertEquals('Test JPY Order', $order->name);
    }

    public function testSaveOrderFailure()
    {
        $this->expectException(\Exception::class);

        $jpyOrderMock = Mockery::mock(JPYOrder::class);

        $jpyOrderMock->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('Database error'));

        $orderRepository = new JPYOrderRepository($jpyOrderMock);

        $data = [
            'id' => 1,
            'name' => 'Test JPY Order',
            'address' => [
                'city' => 'Tokyo',
                'district' => 'Shibuya',
                'street' => '123 Street'
            ],
            'price' => 1000,
            'currency' => 'JPY'
        ];

        $orderRepository->saveOrder($data);
    }

    public function testGetDetailByOrderIdSuccess()
    {
        $jpyOrderMock = Mockery::mock(JPYOrder::class);

        $jpyOrderMock->shouldReceive('where')
            ->once()
            ->with('order_id', 1)
            ->andReturnSelf();

        $jpyOrderMock->shouldReceive('first')
            ->once()
            ->andReturn((object) [
                'id' => 1,
                'order_id' => 1,
                'name' => 'Test JPY Order',
                'address' => '{"city":"Tokyo","district":"Shibuya","street":"123 Street"}',
                'price' => 1000,
                'created_at' => now()
            ]);

        $orderRepository = new JPYOrderRepository($jpyOrderMock);

        $order = $orderRepository->getDetailByOrderId(1);

        $this->assertEquals(1, $order->order_id);
        $this->assertEquals('Test JPY Order', $order->name);
    }

    public function testGetDetailByOrderIdNotFound()
    {
        $jpyOrderMock = Mockery::mock(JPYOrder::class);

        $jpyOrderMock->shouldReceive('where')
            ->once()
            ->with('order_id', 999)
            ->andReturnSelf();

        $jpyOrderMock->shouldReceive('first')
            ->once()
            ->andReturn(null);

        $orderRepository = new JPYOrderRepository($jpyOrderMock);

        $order = $orderRepository->getDetailByOrderId(999);

        $this->assertNull($order);
    }

    protected function tearDown(): void
    {
        Mockery::close();  
        parent::tearDown(); 
    }
}
