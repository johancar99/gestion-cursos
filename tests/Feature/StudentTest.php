<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ejecutar el seeder para crear el usuario administrador
    $this->seed(DatabaseSeeder::class);
});

describe('Student Endpoints', function () {
    
    test('can create student', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $studentData = [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan.perez@example.com',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/students', $studentData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'created_at',
                        'updated_at',
                    ]
                ]);

        $this->assertDatabaseHas('students', $studentData);
    });

    test('cannot create student with duplicate email', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $studentData = [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan.perez@example.com',
        ];

        // Crear primer estudiante
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/students', $studentData);

        // Intentar crear segundo estudiante con mismo email
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/students', $studentData);

        $response->assertStatus(422)
                ->assertJson([
                    'message' => 'El email ya está registrado',
                    'errors' => [
                        'email' => ['El email ya está registrado']
                    ],
                ]);
    });

    test('can get student', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $studentData = [
            'first_name' => 'María',
            'last_name' => 'García',
            'email' => 'maria.garcia@example.com',
        ];

        $createResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/students', $studentData);
        $studentId = $createResponse->json('data.id');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/v1/students/{$studentId}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'created_at',
                        'updated_at',
                    ]
                ]);
    });

    test('can get all students', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $studentsData = [
            [
                'first_name' => 'Juan',
                'last_name' => 'Pérez',
                'email' => 'juan.perez@example.com',
            ],
            [
                'first_name' => 'María',
                'last_name' => 'García',
                'email' => 'maria.garcia@example.com',
            ],
        ];

        foreach ($studentsData as $studentData) {
            $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->postJson('/api/v1/students', $studentData);
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/students/all');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'first_name',
                            'last_name',
                            'email',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]);

        $this->assertCount(2, $response->json('data'));
    });

    test('can update student', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $studentData = [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan.perez@example.com',
        ];

        $createResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/students', $studentData);
        $studentId = $createResponse->json('data.id');

        $updateData = [
            'first_name' => 'Juan Carlos',
            'last_name' => 'Pérez López',
            'email' => 'juan.carlos.perez@example.com',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/v1/students/{$studentId}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Estudiante actualizado exitosamente'
                ]);

        $this->assertDatabaseHas('students', $updateData);
    });

    test('can delete student', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $studentData = [
            'first_name' => 'Ana',
            'last_name' => 'Martínez',
            'email' => 'ana.martinez@example.com',
        ];

        $createResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/students', $studentData);
        $studentId = $createResponse->json('data.id');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/v1/students/{$studentId}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Estudiante eliminado exitosamente'
                ]);

        $this->assertDatabaseMissing('students', ['id' => $studentId]);
    });

    test('validation errors', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $invalidData = [
            'first_name' => '',
            'last_name' => '',
            'email' => 'invalid-email',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/students', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['first_name', 'last_name', 'email']);
    });

    test('can get students with filters', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $studentsData = [
            [
                'first_name' => 'Juan',
                'last_name' => 'Pérez',
                'email' => 'juan.perez@example.com',
            ],
            [
                'first_name' => 'María',
                'last_name' => 'García',
                'email' => 'maria.garcia@example.com',
            ],
            [
                'first_name' => 'Carlos',
                'last_name' => 'López',
                'email' => 'carlos.lopez@example.com',
            ],
        ];

        foreach ($studentsData as $studentData) {
            $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->postJson('/api/v1/students', $studentData);
        }

        // Test filtro por nombre
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/students?first_name=Juan');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Juan', $response->json('data.0.first_name'));

        // Test filtro por apellido
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/students?last_name=García');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('García', $response->json('data.0.last_name'));

        // Test filtro por email
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/students?email=carlos');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('carlos.lopez@example.com', $response->json('data.0.email'));

        // Test filtro por nombre completo (busca en nombre y apellido)
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/students?name=Juan');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Juan', $response->json('data.0.first_name'));
    });

    test('can get students with multiple filters', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $studentsData = [
            [
                'first_name' => 'Juan',
                'last_name' => 'Pérez',
                'email' => 'juan.perez@example.com',
            ],
            [
                'first_name' => 'Juan',
                'last_name' => 'García',
                'email' => 'juan.garcia@example.com',
            ],
        ];

        foreach ($studentsData as $studentData) {
            $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->postJson('/api/v1/students', $studentData);
        }

        // Test múltiples filtros
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/students?first_name=Juan&last_name=Pérez');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Juan', $response->json('data.0.first_name'));
        $this->assertEquals('Pérez', $response->json('data.0.last_name'));
    });
}); 