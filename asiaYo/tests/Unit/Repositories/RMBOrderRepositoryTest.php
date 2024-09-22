<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\RMBOrderRepository;
use App\Models\RMBOrder;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RMBOrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSaveOrderSuccess()
    {
        $rmbOrderMock = Mockery::mock(RMBOrder::class);

        $rmbOrderMock->shouldReceive('create')
            ->once()
            ->withArgs(function ($args) {
                return $args['order_id'] === 1 &&
                       $args['name'] === 'Test RMB Order' &&
                       json_decode($args['address']) == (object) [
                           'city' => 'Beijing',
                           'district' => 'Haidian',
                           'street' => '789 Road'
                       ] &&
                       $args['price'] === 2000;
            })
            ->andReturn((object) [
                'id' => 1,
                'order_id' => 1,
                'name' => 'Test RMB Order',
                'address' => '{"city":"Beijing","district":"Haidian","street":"789 Road"}',
                'price' => 2000,
                'created_at' => now()
            ]);

        $orderRepository = new RMBOrderRepository($rmbOrderMock);

        $data = [
            'id' => 1,
            'name' => 'Test RMB Order',
            'address' => [
                'city' => 'Beijing',
                'district' => 'Haidian',
                'street' => '789 Road'
            ],
            'price' => 2000,
            'currency' => 'RMB'
        ];

        $order = $orderRepository->saveOrder($data);

        $this->assertEquals(1, $order->id);
        $this->assertEquals('Test RMB Order', $order->name);
    }

    public function testSaveOrderFailure()
    {
        $this->expectException(\Exception::class);

        $rmbOrderMock = Mockery::mock(RMBOrder::class);

        $rmbOrderMock->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('Database error'));

        $orderRepository = new RMBOrderRepository($rmbOrderMock);

        $data = [
            'id' => 1,
            'name' => 'Test RMB Order',
            'address' => [
                'city' => 'Beijing',
                'district' => 'Haidian',
                'street' => '789 Road'
            ],
            'price' => 2000,
            'currency' => 'RMB'
        ];

        $orderRepository->saveOrder($data);
    }

    public function testGetDetailByOrderIdSuccess()
    {
        $rmbOrderMock = Mockery::mock(RMBOrder::class);
    
        // 模擬 `where` 方法並返回一個假訂單
        $rmbOrderMock->shouldReceive('where')
            ->once()
            ->with('order_id', 1)
            ->andReturnSelf(); // 返回自身以便連接到 `first`
    
        $rmbOrderMock->shouldReceive('first')
            ->once()
            ->andReturn((object) [
                'id' => 1,
                'order_id' => 1,
                'name' => 'Test RMB Order',
                'address' => '{"city":"Beijing","district":"Haidian","street":"789 Road"}',
                'price' => 2000,
                'created_at' => now()
            ]);
    
        $orderRepository = new RMBOrderRepository($rmbOrderMock);
    
        $order = $orderRepository->getDetailByOrderId(1);
    
        $this->assertEquals(1, $order->order_id);
        $this->assertEquals('Test RMB Order', $order->name);
    }
    
    public function testGetDetailByOrderIdNotFound()
    {
        $rmbOrderMock = Mockery::mock(RMBOrder::class);
    
        $rmbOrderMock->shouldReceive('where')
            ->once()
            ->with('order_id', 999)
            ->andReturnSelf(); 
    
        $rmbOrderMock->shouldReceive('first')
            ->once()
            ->andReturn(null); 
    
        $orderRepository = new RMBOrderRepository($rmbOrderMock);
    
        $order = $orderRepository->getDetailByOrderId(999);
    
        $this->assertNull($order);
    }
    
    protected function tearDown(): void
    {
        Mockery::close();  
        parent::tearDown(); 
    }
}
