<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\USDOrderRepository;
use App\Models\USDOrder;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class USDOrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSaveOrderSuccess()
    {
        $usdOrderMock = Mockery::mock(USDOrder::class);

        $usdOrderMock->shouldReceive('create')
            ->once()
            ->withArgs(function ($args) {
                return $args['order_id'] === 1 &&
                       $args['name'] === 'Test USD Order' &&
                       json_decode($args['address']) == (object) [
                           'city' => 'New York',
                           'district' => 'Manhattan',
                           'street' => '456 Avenue'
                       ] &&
                       $args['price'] === 5000;
            })
            ->andReturn((object) [
                'id' => 1,
                'order_id' => 1,
                'name' => 'Test USD Order',
                'address' => '{"city":"New York","district":"Manhattan","street":"456 Avenue"}',
                'price' => 5000,
                'created_at' => now()
            ]);

        $orderRepository = new USDOrderRepository($usdOrderMock);

        $data = [
            'id' => 1,
            'name' => 'Test USD Order',
            'address' => [
                'city' => 'New York',
                'district' => 'Manhattan',
                'street' => '456 Avenue'
            ],
            'price' => 5000,
            'currency' => 'USD'
        ];

        $order = $orderRepository->saveOrder($data);

        $this->assertEquals(1, $order->id);
        $this->assertEquals('Test USD Order', $order->name);
    }

    public function testSaveOrderFailure()
    {
        $this->expectException(\Exception::class);

        $usdOrderMock = Mockery::mock(USDOrder::class);

        $usdOrderMock->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('Database error'));

        $orderRepository = new USDOrderRepository($usdOrderMock);

        $data = [
            'id' => 1,
            'name' => 'Test USD Order',
            'address' => [
                'city' => 'New York',
                'district' => 'Manhattan',
                'street' => '456 Avenue'
            ],
            'price' => 5000,
            'currency' => 'USD'
        ];

        $orderRepository->saveOrder($data);
    }

    public function testGetDetailByOrderIdSuccess()
    {
        $usdOrderMock = Mockery::mock(USDOrder::class);
    
        $usdOrderMock->shouldReceive('where')
            ->once()
            ->with('order_id', 1)
            ->andReturnSelf();
    
        $usdOrderMock->shouldReceive('first')
            ->once()
            ->andReturn((object) [
                'id' => 1,
                'order_id' => 1,
                'name' => 'Test USD Order',
                'address' => '{"city":"New York","district":"Manhattan","street":"456 Avenue"}',
                'price' => 2000,
                'created_at' => now()
            ]);
    
        $orderRepository = new USDOrderRepository($usdOrderMock);
    
        $order = $orderRepository->getDetailByOrderId(1);
    
        $this->assertEquals(1, $order->order_id);
        $this->assertEquals('Test USD Order', $order->name);
    }
    
    public function testGetDetailByOrderIdNotFound()
    {
        $usdOrderMock = Mockery::mock(USDOrder::class);
    
        $usdOrderMock->shouldReceive('where')
            ->once()
            ->with('order_id', 999)
            ->andReturnSelf();
    
        $usdOrderMock->shouldReceive('first')
            ->once()
            ->andReturn(null);
    
        $orderRepository = new USDOrderRepository($usdOrderMock);
    
        $order = $orderRepository->getDetailByOrderId(999);
    
        $this->assertNull($order);
    }
    
    protected function tearDown(): void
    {
        Mockery::close();  
        parent::tearDown(); 
    }
}
