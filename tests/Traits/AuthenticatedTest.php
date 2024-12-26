<?php

namespace Tests\Traits;

trait AuthenticatedTest
{
    protected $adminCredentials = [
        'email' => 'pepperoni@mail.ru',
        'password' => 'pepperoni',
    ];
    
    protected $userCredentials = [
        'email' => 'vasya@mail.ru',
        'password' => 'vasya',
    ];

    public function adminRequest()
    {
        $loginResponse = $this->postJson('/api/login', $this->adminCredentials);

        $loginResponse->assertStatus(200)
                      ->assertJsonStructure([
                          'access_token',
                          'token_type',
                      ]);

        $accessToken = $loginResponse->json('access_token');

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ]);
    }
    
    public function userRequest()
    {
        $loginResponse = $this->postJson('/api/login', $this->userCredentials);

        $loginResponse->assertStatus(200)
                      ->assertJsonStructure([
                          'access_token',
                          'token_type',
                      ]);

        $accessToken = $loginResponse->json('access_token');

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
        ]);
    }
}
