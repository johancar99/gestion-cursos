<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ejecutar el seeder para crear el usuario administrador
    $this->seed(DatabaseSeeder::class);
});

test('debug login', function () {
    $loginData = [
        'email' => 'admin@example.com',
        'password' => 'password123'
    ];
    
    $response = $this->postJson('/api/v1/auth/login', $loginData);
    
    // Imprimir la respuesta para debug
    dump($response->json());
    
    $response->assertStatus(200);
}); 