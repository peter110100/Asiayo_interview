<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\MYROrderRepository;
use App\Models\MYROrder;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MYROrderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSaveOrderSuccess()
    {
        $myrOrderMock = Mockery::mock(MYROrder::class);

        $myrOrderMock->shouldReceive('create')
            ->once()
            ->withArgs(function ($args) {
                return $args['order_id'] === 1 &&
                       $args['name'] === 'Test MYR Order' &&
                       json_decode($args['address']) == (object) [
                           'city' => 'Kuala Lumpur',
                           'district' => 'Bangsar',
                           'street' => '321 Lane'
                       ] &&
                       $args['price'] === 1500;
            })
            ->andReturn((object) [
                'id' => 1,
                'order_id' => 1,
                'name' => 'Test MYR Order',
                'address' => '{"city":"Kuala Lumpur","district":"Bangsar","street":"321 Lane"}',
                'price' => 1500,
                'created_at' => now()
            ]);

        $orderRepository = new MYROrderRepository($myrOrderMock);

        $data = [
            'id' => 1,
            'name' => 'Test MYR Order',
            'address' => [
                'city' => 'Kuala Lumpur',
                'district' => 'Bangsar',
                'street' => '321 Lane'
            ],
            'price' => 1500,
            'currency' => 'MYR'
        ];

        $order = $orderRepository->saveOrder($data);

        $this->assertEquals(1, $order->id);
        $this->assertEquals('Test MYR Order', $order->name);
    }

    public function testSaveOrderFailure()
    {
        $this->expectException(\Exception::class);

        $myrOrderMock = Mockery::mock(MYROrder::class);

        $myrOrderMock->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('Database error'));

        $orderRepository = new MYROrderRepository($myrOrderMock);

        $data = [
            'id' => 1,
            'name' => 'Test MYR Order',
            'address' => [
                'city' => 'Kuala Lumpur',
                'district' => 'Bangsar',
                'street' => '321 Lane'
            ],
            'price' => 1500,
            'currency' => 'MYR'
        ];

        $orderRepository->saveOrder($data);
    }

    public function testGetDetailByOrderIdSuccess()
    {
        $myrOrderMock = Mockery::mock(MYROrder::class);
    
        $myrOrderMock->shouldReceive('where')
            ->once()
            ->with('order_id', 1)
            ->andReturnSelf();
    
        $myrOrderMock->shouldReceive('first')
            ->once()
            ->andReturn((object) [
                'id' => 1,
                'order_id' => 1,
                'name' => 'Test MYR Order',
                'address' => '{"city":"Kuala Lumpur","district":"Bangsar","street":"321 Road"}',
                'price' => 1500,
                'created_at' => now()
            ]);
    
        $orderRepository = new MYROrderRepository($myrOrderMock);
    
        $order = $orderRepository->getDetailByOrderId(1);
    
        $this->assertEquals(1, $order->order_id);
        $this->assertEquals('Test MYR Order', $order->name);
    }
    
    public function testGetDetailByOrderIdNotFound()
    {
        $myrOrderMock = Mockery::mock(MYROrder::class);
    
        $myrOrderMock->shouldReceive('where')
            ->once()
            ->with('order_id', 999)
            ->andReturnSelf();
    
        $myrOrderMock->shouldReceive('first')
            ->once()
            ->andReturn(null);
    
        $orderRepository = new MYROrderRepository($myrOrderMock);
    
        $order = $orderRepository->getDetailByOrderId(999);
    
        $this->assertNull($order);
    }
        
    protected function tearDown(): void
    {
        Mockery::close();  
        parent::tearDown(); 
    }
}
