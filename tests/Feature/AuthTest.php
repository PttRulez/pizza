<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function user_can_login(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'pepperoni@mail.ru',
            'password' => 'pepperoni',
        ]);
        
        $response->assertStatus(200);
        
        $this->assertNotEmpty($response->json('access_token'));
        
        $response->assertJsonPath('token_type', 'bearer');
    }
    
    #[Test]
    public function wrong_credential_get_401(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }
}
