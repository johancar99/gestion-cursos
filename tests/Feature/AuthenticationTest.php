<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ejecutar el seeder para crear el usuario administrador
    $this->seed(DatabaseSeeder::class);
});

describe('Authentication', function () {
    
    test('can login successfully', function () {
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $response = $this->postJson('/api/v1/auth/login', $loginData);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'token',
                    'token_type',
                    'expires_in',
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ])
            ->assertJson([
                'message' => 'Login exitoso',
                'data' => [
                    'token_type' => 'Bearer',
                    'user' => [
                        'name' => 'Administrador',
                        'email' => 'admin@example.com'
                    ]
                ]
            ]);
    });

    test('can access protected route with token', function () {
        // Primero hacer login
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        // Intentar acceder a una ruta protegida
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users/1');
        
        // Debería devolver 404 porque el usuario 1 no existe, pero no 401
        $response->assertStatus(404);
    });
}); 