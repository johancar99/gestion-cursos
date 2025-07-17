<?php

use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
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

    // Crear curso y estudiante para los tests
    $this->course = Course::factory()->create();
    $this->student = Student::factory()->create();
});

describe('Enrollment Endpoints', function () {
    
    test('can create enrollment', function () {
        $enrollmentData = [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v1/enrollments', $enrollmentData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'student_id',
                        'course_id',
                        'enrolled_at'
                    ]
                ]);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id
        ]);
    });

    test('cannot create duplicate enrollment', function () {
        // Crear primera inscripción
        $enrollmentData = [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v1/enrollments', $enrollmentData);

        // Intentar crear la misma inscripción nuevamente
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v1/enrollments', $enrollmentData);

        $response->assertStatus(400)
                ->assertJson([
                    'message' => 'El estudiante ya está inscrito en este curso'
                ]);
    });

    test('can get all enrollments', function () {
        Enrollment::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson('/api/v1/enrollments');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'student_id',
                            'course_id',
                            'enrolled_at'
                        ]
                    ]
                ]);

        $this->assertCount(3, $response->json('data'));
    });

    test('can get enrollment by id', function () {
        $enrollment = Enrollment::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson("/api/v1/enrollments/{$enrollment->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'student_id',
                        'course_id',
                        'enrolled_at'
                    ]
                ]);
    });

    test('can get enrollments by course', function () {
        $course = Course::factory()->create();
        $students = Student::factory()->count(3)->create();
        
        foreach ($students as $student) {
            Enrollment::factory()->create([
                'course_id' => $course->id,
                'student_id' => $student->id
            ]);
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson("/api/v1/enrollments/course/{$course->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'student_id',
                            'course_id',
                            'enrolled_at'
                        ]
                    ]
                ]);

        $this->assertCount(3, $response->json('data'));
    });

    test('can get enrollments by student', function () {
        $student = Student::factory()->create();
        $courses = Course::factory()->count(3)->create();
        
        foreach ($courses as $course) {
            Enrollment::factory()->create([
                'course_id' => $course->id,
                'student_id' => $student->id
            ]);
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson("/api/v1/enrollments/student/{$student->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'student_id',
                            'course_id',
                            'enrolled_at'
                        ]
                    ]
                ]);

        $this->assertCount(3, $response->json('data'));
    });

    test('can delete enrollment', function () {
        $enrollment = Enrollment::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->deleteJson("/api/v1/enrollments/{$enrollment->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Inscripción eliminada exitosamente'
                ]);

        $this->assertDatabaseMissing('enrollments', [
            'id' => $enrollment->id
        ]);
    });

    test('cannot create enrollment without authentication', function () {
        $enrollmentData = [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id
        ];

        $response = $this->postJson('/api/v1/enrollments', $enrollmentData);

        $response->assertStatus(401);
    });

    test('cannot create enrollment with non-existent student', function () {
        $enrollmentData = [
            'student_id' => 99999,
            'course_id' => $this->course->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v1/enrollments', $enrollmentData);

        $response->assertStatus(422);
    });

    test('cannot create enrollment with non-existent course', function () {
        $enrollmentData = [
            'student_id' => $this->student->id,
            'course_id' => 99999
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v1/enrollments', $enrollmentData);

        $response->assertStatus(422);
    });

    test('enrollment has enrolled_at timestamp', function () {
        $enrollmentData = [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v1/enrollments', $enrollmentData);

        $response->assertStatus(201);

        $enrollment = $response->json('data');
        $this->assertNotNull($enrollment['enrolled_at']);
        
        // Verificar que la fecha es reciente (dentro de los últimos 5 segundos)
        $enrolledAt = strtotime($enrollment['enrolled_at']);
        $now = time();
        $this->assertLessThanOrEqual(5, abs($now - $enrolledAt));
    });
}); 