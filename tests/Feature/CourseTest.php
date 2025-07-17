<?php

use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ejecutar el seeder para crear el usuario administrador con roles
    $this->seed(DatabaseSeeder::class);
    
    // Usar el usuario admin que ya tiene los permisos
    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'admin@example.com',
        'password' => 'password123'
    ]);

    $this->token = $response->json('data.token');
});

describe('Course Endpoints', function () {
    
    test('can create course', function () {
        $courseData = [
            'title' => 'Curso de Laravel',
            'description' => 'Aprende Laravel desde cero hasta avanzado con proyectos prácticos',
            'start_date' => date('Y-m-d', strtotime('+1 month')),
            'end_date' => date('Y-m-d', strtotime('+3 months'))
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v1/courses', $courseData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'title',
                        'description',
                        'start_date',
                        'end_date',
                        'created_at',
                        'updated_at'
                    ]
                ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Curso de Laravel',
            'description' => 'Aprende Laravel desde cero hasta avanzado con proyectos prácticos'
        ]);
    });

    test('can get all courses', function () {
        Course::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson('/api/v1/courses/all');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'start_date',
                            'end_date',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);

        $this->assertCount(3, $response->json('data'));
    });

    test('can get course by id', function () {
        $course = Course::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson("/api/v1/courses/{$course->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'title',
                        'description',
                        'start_date',
                        'end_date',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    });

    test('can update course', function () {
        $course = Course::factory()->create();

        $updateData = [
            'title' => 'Curso Actualizado',
            'description' => 'Descripción actualizada del curso'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->putJson("/api/v1/courses/{$course->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'title',
                        'description',
                        'start_date',
                        'end_date',
                        'created_at',
                        'updated_at'
                    ]
                ]);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Curso Actualizado',
            'description' => 'Descripción actualizada del curso'
        ]);
    });

    test('can delete course', function () {
        $course = Course::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->deleteJson("/api/v1/courses/{$course->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Curso eliminado exitosamente'
                ]);

        $this->assertDatabaseMissing('courses', [
            'id' => $course->id
        ]);
    });

    test('cannot create course without authentication', function () {
        $courseData = [
            'title' => 'Curso de Laravel',
            'description' => 'Aprende Laravel desde cero hasta avanzado con proyectos prácticos',
            'start_date' => date('Y-m-d', strtotime('+1 month')),
            'end_date' => date('Y-m-d', strtotime('+3 months'))
        ];

        $response = $this->postJson('/api/v1/courses', $courseData);

        $response->assertStatus(401);
    });

    test('cannot create course with invalid dates', function () {
        $courseData = [
            'title' => 'Curso de Laravel',
            'description' => 'Aprende Laravel desde cero hasta avanzado con proyectos prácticos',
            'start_date' => date('Y-m-d', strtotime('+3 months')),
            'end_date' => date('Y-m-d', strtotime('+1 month')) // Fecha de fin anterior a la de inicio
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v1/courses', $courseData);

        $response->assertStatus(422);
    });

    test('can filter courses by title', function () {
        Course::factory()->create(['title' => 'Laravel Course']);
        Course::factory()->create(['title' => 'PHP Basics']);
        Course::factory()->create(['title' => 'Advanced Laravel']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson('/api/v1/courses?title=Laravel');

        $response->assertStatus(200);
        
        $courses = $response->json('data');
        $this->assertCount(2, $courses);
        
        foreach ($courses as $course) {
            $this->assertStringContainsString('Laravel', $course['title']);
        }
    });
}); 