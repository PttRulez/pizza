<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AuthenticatedTest;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedTest;
    
    #[Test]
    public function user_can_order(): void
    {
        $response = $this->userRequest()->post('/api/order', [
            "address" => "улица Гангстеров д.3 кв. 666",
            "phone_number" => "777-66-55",
            "email" => "sasha@mail.ru",
            "delivery_time" => "2024-12-30 20:44:11",
            "items" => [
                [
                    "good_id" => 1,
                    "quantity" => 12
                ],
                [
                    "good_id" => 3,
                    "quantity" => 4
                ],
                [
                    "good_id" => 4,
                    "quantity" => 5
                ]
            ]
        ]);
        
        $response->assertStatus(200);
        
        $this->assertDatabaseCount('orders', 1);
    }
    
    #[Test]
    public function order_is_validated_for_limits(): void
    {
        $response = $this->userRequest()->post('/api/order', [
            "address" => "улица Гангстеров д.3 кв. 666",
            "phone_number" => "777-66-55",
            "email" => "sasha@mail.ru",
            "delivery_time" => "2024-12-30 20:44:11",
            "items" => [
                [
                    "good_id" => 1,
                    "quantity" => 12
                ],
                [
                    "good_id" => 3,
                    "quantity" => 7
                ],
                [
                    "good_id" => 4,
                    "quantity" => 7
                ]
            ]
        ]);
        
        $response->assertStatus(400);
        
        $this->assertDatabaseCount('orders', 0);
    }
    
    #[Test]
    public function cannot_order_non_existent_good(): void
    {
        $response = $this->userRequest()->post('/api/order', [
            "address" => "улица Гангстеров д.3 кв. 666",
            "phone_number" => "777-66-55",
            "email" => "sasha@mail.ru",
            "delivery_time" => "2024-12-30 20:44:11",
            "items" => [
                [
                    "good_id" => 44,
                    "quantity" => 1
                ],
                [
                    "good_id" => 3,
                    "quantity" => 1
                ],
                [
                    "good_id" => 4,
                    "quantity" => 1
                ]
            ]
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('items.0.good_id');
        
        $this->assertDatabaseCount('orders', 0);
    }
    
    #[Test]
    public function user_can_check_his_order_list(): void
    {
        $response = $this->userRequest()->post('/api/order', [
            "address" => "улица Гангстеров д.3 кв. 666",
            "phone_number" => "777-66-55",
            "email" => "sasha@mail.ru",
            "delivery_time" => "2024-12-30 20:44:11",
            "items" => [
                [
                    "good_id" => 1,
                    "quantity" => 12
                ],
                [
                    "good_id" => 3,
                    "quantity" => 4
                ],
                [
                    "good_id" => 4,
                    "quantity" => 5
                ]
            ]
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount('orders', 1);
        
        $response = $this->userRequest()->post('/api/order', [
            "address" => "улица Гангстеров д.3 кв. 666",
            "phone_number" => "777-66-55",
            "email" => "sasha@mail.ru",
            "delivery_time" => "2024-12-30 20:44:11",
            "items" => [
                [
                    "good_id" => 1,
                    "quantity" => 12
                ],
                [
                    "good_id" => 3,
                    "quantity" => 2
                ],
                [
                    "good_id" => 4,
                    "quantity" => 2
                ]
            ]
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount('orders', 2);
        
        $response = $this->userRequest()->get('/api/order');
        
        $response->assertJsonCount(2, $key = null);
        $response->assertJsonStructure([
            [
                'address',
                "phone_number",
                "goods" => [[
                    "name",
                    "pivot"
                ]]
            ]
        ]);
    }

    #[Test]
    public function admin_can_check_his_order_list(): void
    {
        $response = $this->userRequest()->post('/api/order', [
            "address" => "улица Гангстеров д.3 кв. 666",
            "phone_number" => "777-66-55",
            "email" => "sasha@mail.ru",
            "delivery_time" => "2024-12-30 20:44:11",
            "items" => [
                [
                    "good_id" => 1,
                    "quantity" => 12
                ],
                [
                    "good_id" => 3,
                    "quantity" => 4
                ],
                [
                    "good_id" => 4,
                    "quantity" => 5
                ]
            ]
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount('orders', 1);
        
        $response = $this->userRequest()->post('/api/order', [
            "address" => "улица Гангстеров д.3 кв. 666",
            "phone_number" => "777-66-55",
            "email" => "sasha@mail.ru",
            "delivery_time" => "2024-12-30 20:44:11",
            "items" => [
                [
                    "good_id" => 1,
                    "quantity" => 12
                ],
                [
                    "good_id" => 3,
                    "quantity" => 2
                ],
                [
                    "good_id" => 4,
                    "quantity" => 2
                ]
            ]
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount('orders', 2);
        
        $response = $this->adminRequest()->get('/api/admin/order');
        $response->assertJsonCount(2, $key = null);
        
        $response->assertJsonStructure([[
            "address",
            "status",
            "user" => [
                "name"
            ]
        ]]);
    }
    
    #[Test]
    public function admin_can_change_order_status(): void
    {
        $response = $this->userRequest()->post('/api/order', [
            "address" => "улица Гангстеров д.3 кв. 666",
            "phone_number" => "777-66-55",
            "email" => "sasha@mail.ru",
            "delivery_time" => "2024-12-30 20:44:11",
            "items" => [
                [
                    "good_id" => 1,
                    "quantity" => 12
                ],
                [
                    "good_id" => 3,
                    "quantity" => 4
                ],
                [
                    "good_id" => 4,
                    "quantity" => 5
                ]
            ]
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount('orders', 1);
        
        $order = Order::findOrFail(1);
        $this->assertEquals($order->status, OrderStatus::Processing->value);
        
        $response = $this->adminRequest()->patch('/api/admin/order/1/change-status', [
            "status" => OrderStatus::Delivering->value
        ]);
        
        $response->assertStatus(200);
        
        $order = Order::findOrFail(1);
        $this->assertEquals($order->status, OrderStatus::Delivering->value);
    }
}
