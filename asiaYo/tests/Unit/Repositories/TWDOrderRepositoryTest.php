<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\TWDOrderRepository;
use App\Models\TWDOrder;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TWDOrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSaveOrderSuccess()
    {
        $twdOrderMock = Mockery::mock(TWDOrder::class);

        $twdOrderMock->shouldReceive('create')
            ->once()
            ->withArgs(function ($args) {
                return $args['order_id'] === 1 &&
                       $args['name'] === 'Test TWD Order' &&
                       json_decode($args['address']) == (object) [
                           'city' => 'Taipei',
                           'district' => 'Daan',
                           'street' => '789 Road'
                       ] &&
                       $args['price'] === 3000;
            })
            ->andReturn((object) [
                'id' => 1,
                'order_id' => 1,
                'name' => 'Test TWD Order',
                'address' => '{"city":"Taipei","district":"Daan","street":"789 Road"}',
                'price' => 3000,
                'created_at' => now()
            ]);

        $orderRepository = new TWDOrderRepository($twdOrderMock);

        $data = [
            'id' => 1,
            'name' => 'Test TWD Order',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => '789 Road'
            ],
            'price' => 3000,
            'currency' => 'TWD'
        ];

        $order = $orderRepository->saveOrder($data);

        $this->assertEquals(1, $order->id);
        $this->assertEquals('Test TWD Order', $order->name);
    }

    public function testSaveOrderFailure()
    {
        $this->expectException(\Exception::class);

        $twdOrderMock = Mockery::mock(TWDOrder::class);

        $twdOrderMock->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('Database error'));

        $orderRepository = new TWDOrderRepository($twdOrderMock);

        $data = [
            'id' => 1,
            'name' => 'Test TWD Order',
            'address' => [
                'city' => 'Taipei',
                'district' => 'Daan',
                'street' => '789 Road'
            ],
            'price' => 3000,
            'currency' => 'TWD'
        ];

        $orderRepository->saveOrder($data);
    }

    public function testGetDetailByOrderIdSuccess()
    {
        $twdOrderMock = Mockery::mock(TWDOrder::class);
    
        $twdOrderMock->shouldReceive('where')
            ->once()
            ->with('order_id', 1)
            ->andReturnSelf();
    
        $twdOrderMock->shouldReceive('first')
            ->once()
            ->andReturn((object) [
                'id' => 1,
                'order_id' => 1,
                'name' => 'Test TWD Order',
                'address' => '{"city":"Taipei","district":"Xinyi","street":"987 Lane"}',
                'price' => 1200,
                'created_at' => now()
            ]);
    
        $orderRepository = new TWDOrderRepository($twdOrderMock);
    
        $order = $orderRepository->getDetailByOrderId(1);
    
        $this->assertEquals(1, $order->order_id);
        $this->assertEquals('Test TWD Order', $order->name);
    }
    
    public function testGetDetailByOrderIdNotFound()
    {
        $twdOrderMock = Mockery::mock(TWDOrder::class);
    
        $twdOrderMock->shouldReceive('where')
            ->once()
            ->with('order_id', 999)
            ->andReturnSelf();
    
        $twdOrderMock->shouldReceive('first')
            ->once()
            ->andReturn(null);
    
        $orderRepository = new TWDOrderRepository($twdOrderMock);
    
        $order = $orderRepository->getDetailByOrderId(999);
    
        $this->assertNull($order);
    }
    
    protected function tearDown(): void
    {
        Mockery::close();  
        parent::tearDown(); 
    }
}
