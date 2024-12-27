<?php

namespace Tests\Feature;

use App\Models\Good;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AuthenticatedTest;

class GoodsTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedTest;
    
    #[Test]
    public function admin_can_create_good(): void
    {
        $response = $this->adminRequest()->post('/api/admin/goods', [
            'name' => 'Вода',
            'price' => 243.67,
            'type' => 'drink'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('goods', [
            'name' => 'Вода',
            'price' => 243.67,
            'type' => 'drink'
        ]);
    }
    
    #[Test]
    public function regular_user_cannot_create_good(): void
    {
        $response = $this->userRequest()->post('/api/admin/goods', [
            'name' => 'Вода',
            'price' => 243.67,
            'type' => 'drink'
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function admin_can_update_good(): void
    {
        $data = [
            'name' => 'Кола',
            'price' => 222.22,
        ];
        
        $response = $this->adminRequest()->patch('/api/admin/goods/1', $data);
        $response->assertStatus(200);
        
        $drink = Good::findOrFail(1);
        $this->assertEquals($data['name'], $drink->name);
        $this->assertEquals($data['price'], $drink->price);
    }

    #[Test]
    public function regular_user_cannot_update_good(): void
    {
        $data = [
            'name' => 'Кола',
            'price' => 222.22,
        ];
        
        $response = $this->userRequest()->patch('/api/admin/goods/1', $data);
        $response->assertStatus(403);
    }

    #[Test]
    public function regular_user_cannot_delete_good(): void
    {
        $response = $this->userRequest()->delete('/api/admin/goods/1');
        
        $response->assertStatus(403);
        
        $this->assertDatabaseHas('goods', ['id' => 1]);
    }

    #[Test]
    public function admin_can_delete_good(): void
    {
        $response = $this->adminRequest()->delete('/api/admin/goods/1');
        
        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('goods', ['id' => 1]);
    }
}
