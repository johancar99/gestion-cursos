<?php

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\UserId;
use App\Infrastructure\Persistence\Eloquent\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ejecutar el seeder para crear el usuario administrador
    $this->seed(DatabaseSeeder::class);
});

describe('User Endpoints', function () {
    
    test('can create a new user', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'role' => 'secretary'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'message' => 'Usuario creado exitosamente',
                'data' => [
                    'name' => 'Juan Pérez',
                    'email' => 'juan@example.com'
                ]
            ]);

        // Verificar que el usuario se guardó en la base de datos
        $this->assertDatabaseHas('users', [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com'
        ]);
        // Verificar que el usuario tiene el rol asignado
        $userId = $response->json('data.id');
        $user = \App\Models\User::find($userId);
        $this->assertTrue($user->hasRole('secretary'));
    });

    test('cannot create user with invalid email', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'invalid-email',
            'password' => 'password123'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });

    test('cannot create user with short password', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => '123'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    });

    test('cannot create user with duplicate email', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        // Crear un usuario primero
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'role' => 'secretary'
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData);

        // Intentar crear otro usuario con el mismo email
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });

    test('can get user by id', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        // Crear un usuario primero
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'role' => 'secretary'
        ];

        $createResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData);
        $userId = $createResponse->json('data.id');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/v1/users/{$userId}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $userId,
                    'name' => 'Juan Pérez',
                    'email' => 'juan@example.com'
                ]
            ]);
    });

    test('returns 404 for non-existent user', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users/non-existent-id');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'User not found'
            ]);
    });

    test('can update user', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        // Crear un usuario primero
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'role' => 'secretary'
        ];

        $createResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData);
        $userId = $createResponse->json('data.id');

        // Actualizar el usuario
        $updateData = [
            'name' => 'Juan Carlos Pérez',
            'email' => 'juancarlos@example.com',
            'role' => 'admin'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/v1/users/{$userId}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Usuario actualizado exitosamente',
                'data' => [
                    'id' => $userId,
                    'name' => 'Juan Carlos Pérez',
                    'email' => 'juancarlos@example.com'
                ]
            ]);

        // Verificar que se actualizó en la base de datos
        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'name' => 'Juan Carlos Pérez',
            'email' => 'juancarlos@example.com'
        ]);
        // Verificar que el usuario tiene el nuevo rol asignado
        $user = \App\Models\User::find($userId);
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('secretary'));
    });

    test('can update user with partial data', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        // Crear un usuario primero
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'role' => 'secretary'
        ];

        $createResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData);
        $userId = $createResponse->json('data.id');

        // Actualizar solo el nombre
        $updateData = [
            'name' => 'Juan Carlos Pérez'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/v1/users/{$userId}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $userId,
                    'name' => 'Juan Carlos Pérez',
                    'email' => 'juan@example.com' // Email no cambió
                ]
            ]);
    });

    test('cannot update user with duplicate email', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        // Crear dos usuarios
        $userData1 = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'role' => 'secretary'
        ];

        $userData2 = [
            'name' => 'María García',
            'email' => 'maria@example.com',
            'password' => 'password123',
            'role' => 'admin'
        ];

        $createResponse1 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData1);
        $userId1 = $createResponse1->json('data.id');

        $createResponse2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData2);
        $userId2 = $createResponse2->json('data.id');

        // Intentar actualizar el segundo usuario con el email del primero
        $updateData = [
            'email' => 'juan@example.com'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/v1/users/{$userId2}", $updateData);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'El email ya está registrado',
                'errors' => [
                    'email' => ['El email ya está registrado']
                ]
            ]);
    });

    test('can delete user', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        // Crear un usuario primero
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'role' => 'secretary'
        ];

        $createResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData);
        $userId = $createResponse->json('data.id');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/v1/users/{$userId}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Usuario eliminado exitosamente'
            ]);

        // Verificar que se eliminó de la base de datos
        $this->assertDatabaseMissing('users', [
            'id' => $userId
        ]);
    });

    test('returns 404 when deleting non-existent user', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/v1/users/non-existent-id');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'User not found'
            ]);
    });

    test('can get all users', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        // Crear algunos usuarios de prueba
        $usersData = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'password' => 'password123',
                'role' => 'secretary'
            ],
            [
                'name' => 'María García',
                'email' => 'maria@example.com',
                'password' => 'password123',
                'role' => 'admin'
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@example.com',
                'password' => 'password123',
                'role' => 'secretary'
            ]
        ];

        foreach ($usersData as $userData) {
            $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->postJson('/api/v1/users', $userData);
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users/all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);

        // Verificar que se devuelven todos los usuarios (incluyendo el admin)
        $responseData = $response->json('data');
        $this->assertCount(4, $responseData); // 3 usuarios creados + 1 admin del seeder

        // Verificar que los usuarios creados están en la respuesta
        $userNames = collect($responseData)->pluck('name')->toArray();
        $this->assertContains('Juan Pérez', $userNames);
        $this->assertContains('María García', $userNames);
        $this->assertContains('Carlos López', $userNames);
        $this->assertContains('Administrador', $userNames); // Usuario del seeder
    });

    test('can get users with filters', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        // Crear algunos usuarios de prueba
        $usersData = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'password' => 'password123',
                'role' => 'secretary'
            ],
            [
                'name' => 'María García',
                'email' => 'maria@example.com',
                'password' => 'password123',
                'role' => 'admin'
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@example.com',
                'password' => 'password123',
                'role' => 'secretary'
            ]
        ];

        foreach ($usersData as $userData) {
            $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->postJson('/api/v1/users', $userData);
        }

        // Test filtro por nombre
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users?name=Juan');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Juan Pérez', $response->json('data.0.name'));

        // Test filtro por email
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users?email=maria');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('maria@example.com', $response->json('data.0.email'));

        // Test filtro por nombre parcial
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users?name=Pérez');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Juan Pérez', $response->json('data.0.name'));
    });

    test('can get users with multiple filters', function () {
        // Primero hacer login para obtener el token
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];
        
        $loginResponse = $this->postJson('/api/v1/auth/login', $loginData);
        $token = $loginResponse->json('data.token');
        
        // Crear algunos usuarios de prueba
        $usersData = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'password' => 'password123',
                'role' => 'secretary'
            ],
            [
                'name' => 'Juan García',
                'email' => 'juan.garcia@example.com',
                'password' => 'password123',
                'role' => 'admin'
            ],
        ];

        foreach ($usersData as $userData) {
            $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->postJson('/api/v1/users', $userData);
        }

        // Test múltiples filtros
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users?name=Juan&email=juan@example.com');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Juan Pérez', $response->json('data.0.name'));
        $this->assertEquals('juan@example.com', $response->json('data.0.email'));
    });
}); 