<?php

namespace Tests\Feature;

use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AuthenticatedTest;

class CartTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedTest;
    
    #[Test]
    public function user_can_add_to_cart(): void
    {
        $response = $this->userRequest()->post('/api/cart', [
            'good_id' => 2,
            'quantity' => 1,
        ]);
        
        $response->assertStatus(200);
        
        $this->assertDatabaseCount('cart_items', 1);
    }
    
    #[Test]
    public function user_cant_add_nonexistent_good_to_cart(): void
    {
        $response = $this->userRequest()->post('/api/cart', [
            'good_id' => 33,
            'quantity' => 1,
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('good_id');
        
        $this->assertDatabaseCount('cart_items', 0);
    }
    
    #[Test]
    public function user_cant_exceed_limits_to_cart(): void
    {
        $response = $this->userRequest()->post('/api/cart', [
            'good_id' => 1,
            'quantity' => 40,
        ]);
        
        $response->assertStatus(400);
        
        $this->assertDatabaseCount('cart_items', 0);
    }
    
    #[Test]
    public function user_can_add_many_to_cart(): void
    {
        $response = $this->userRequest()->post('/api/cart', [
            'good_id' => 1,
            'quantity' => 1,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount('cart_items', 1);
        
        $response = $this->userRequest()->put('/api/cart', [
            [
                'good_id' => 2,
                'quantity' => 4
            ],
            [
                'good_id' => 3,
                'quantity' => 5,
            ],
            [
                'good_id' => 4,
                'quantity' => 3,
            ]
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount('cart_items', 3);
    }
    
    #[Test]
    public function user_cannot_exceed_many_to_cart(): void
    {
        $response = $this->userRequest()->put('/api/cart', [
            [
                'good_id' => 2,
                'quantity' => 4
            ],
            [
                'good_id' => 3,
                'quantity' => 5,
            ],
            [
                'good_id' => 4,
                'quantity' => 8,
            ]
        ]);
        $response->assertStatus(400);
        $this->assertDatabaseCount('cart_items', 0);
    }
}
