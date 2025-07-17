<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Enrollment\DTOs\CreateEnrollmentDTO;
use App\Application\Enrollment\Services\EnrollmentService;
use App\Interfaces\Http\Requests\CreateEnrollmentRequest;
use App\Interfaces\Http\Resources\EnrollmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

/**
 * @group Enrollments
 * 
 * Endpoints para gestión de matrículas del sistema
 */
class EnrollmentController
{
    public function __construct(
        private EnrollmentService $enrollmentService
    ) {
    }

    /**
     * Crear matrícula
     * 
     * Inscribe un estudiante en un curso específico.
     * 
     * @authenticated
     * 
     * @bodyParam student_id string required El ID del estudiante. Example: 1
     * @bodyParam course_id string required El ID del curso. Example: 1
     * 
     * @response 201 scenario="Matrícula creada" {
     *   "message": "Estudiante inscrito exitosamente",
     *   "data": {
     *     "id": 1,
     *     "student_id": 1,
     *     "course_id": 1,
     *     "enrolled_at": "2024-01-01T00:00:00.000000Z",
     *     "student": {
     *       "id": 1,
     *       "first_name": "Juan",
     *       "last_name": "Pérez",
     *       "email": "juan.perez@example.com"
     *     },
     *     "course": {
     *       "id": 1,
     *       "title": "Programación Web",
     *       "description": "Curso completo de programación web",
     *       "start_date": "2024-02-01",
     *       "end_date": "2024-06-30"
     *     }
     *   }
     * }
     * 
     * @response 400 scenario="Datos inválidos" {
     *   "message": "El estudiante ya está inscrito en este curso"
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function store(CreateEnrollmentRequest $request): JsonResponse
    {
        try {
            $dto = new CreateEnrollmentDTO(
                $request->input('student_id'),
                $request->input('course_id')
            );

            $enrollmentResponse = $this->enrollmentService->createEnrollment($dto);

            return response()->json([
                'message' => 'Estudiante inscrito exitosamente',
                'data' => new EnrollmentResource($enrollmentResponse)
            ], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al crear inscripción: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener todas las matrículas
     * 
     * Obtiene una lista de todas las matrículas del sistema.
     * 
     * @authenticated
     * 
     * @response 200 scenario="Lista de matrículas" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "student_id": 1,
     *       "course_id": 1,
     *       "enrolled_at": "2024-01-01T00:00:00.000000Z",
     *       "student": {
     *         "id": 1,
     *         "first_name": "Juan",
     *         "last_name": "Pérez",
     *         "email": "juan.perez@example.com"
     *       },
     *       "course": {
     *         "id": 1,
     *         "title": "Programación Web",
     *         "description": "Curso completo de programación web",
     *         "start_date": "2024-02-01",
     *         "end_date": "2024-06-30"
     *       }
     *     }
     *   ]
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $enrollments = $this->enrollmentService->getAllEnrollments();

            return response()->json([
                'data' => EnrollmentResource::collection($enrollments)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener inscripciones: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener matrícula por ID
     * 
     * Obtiene la información detallada de una matrícula específica.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID de la matrícula. Example: 1
     * 
     * @response 200 scenario="Matrícula encontrada" {
     *   "data": {
     *     "id": 1,
     *     "student_id": 1,
     *     "course_id": 1,
     *     "enrolled_at": "2024-01-01T00:00:00.000000Z",
     *     "student": {
     *       "id": 1,
     *       "first_name": "Juan",
     *       "last_name": "Pérez",
     *       "email": "juan.perez@example.com"
     *     },
     *     "course": {
     *       "id": 1,
     *       "title": "Programación Web",
     *       "description": "Curso completo de programación web",
     *       "start_date": "2024-02-01",
     *       "end_date": "2024-06-30"
     *     }
     *   }
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 404 scenario="Matrícula no encontrada" {
     *   "message": "Matrícula no encontrada"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function show(string $id): JsonResponse
    {
        try {
            $enrollmentResponse = $this->enrollmentService->getEnrollment($id);

            return response()->json([
                'data' => new EnrollmentResource($enrollmentResponse)
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener matrículas por curso
     * 
     * Obtiene todas las matrículas de un curso específico.
     * 
     * @authenticated
     * 
     * @urlParam courseId string required El ID del curso. Example: 1
     * 
     * @response 200 scenario="Matrículas del curso" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "student_id": 1,
     *       "course_id": 1,
     *       "enrolled_at": "2024-01-01T00:00:00.000000Z",
     *       "student": {
     *         "id": 1,
     *         "first_name": "Juan",
     *         "last_name": "Pérez",
     *         "email": "juan.perez@example.com"
     *       },
     *       "course": {
     *         "id": 1,
     *         "title": "Programación Web",
     *         "description": "Curso completo de programación web",
     *         "start_date": "2024-02-01",
     *         "end_date": "2024-06-30"
     *       }
     *     }
     *   ]
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function byCourse(string $courseId): JsonResponse
    {
        try {
            $enrollments = $this->enrollmentService->getEnrollmentsByCourse($courseId);

            return response()->json([
                'data' => EnrollmentResource::collection($enrollments)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener inscripciones por curso: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener matrículas por estudiante
     * 
     * Obtiene todas las matrículas de un estudiante específico.
     * 
     * @authenticated
     * 
     * @urlParam studentId string required El ID del estudiante. Example: 1
     * 
     * @response 200 scenario="Matrículas del estudiante" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "student_id": 1,
     *       "course_id": 1,
     *       "enrolled_at": "2024-01-01T00:00:00.000000Z",
     *       "student": {
     *         "id": 1,
     *         "first_name": "Juan",
     *         "last_name": "Pérez",
     *         "email": "juan.perez@example.com"
     *       },
     *       "course": {
     *         "id": 1,
     *         "title": "Programación Web",
     *         "description": "Curso completo de programación web",
     *         "start_date": "2024-02-01",
     *         "end_date": "2024-06-30"
     *       }
     *     }
     *   ]
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function byStudent(string $studentId): JsonResponse
    {
        try {
            $enrollments = $this->enrollmentService->getEnrollmentsByStudent($studentId);

            return response()->json([
                'data' => EnrollmentResource::collection($enrollments)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener inscripciones por estudiante: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Eliminar matrícula
     * 
     * Elimina una matrícula del sistema.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID de la matrícula. Example: 1
     * 
     * @response 200 scenario="Matrícula eliminada" {
     *   "message": "Inscripción eliminada exitosamente"
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 404 scenario="Matrícula no encontrada" {
     *   "message": "Matrícula no encontrada"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->enrollmentService->deleteEnrollment($id);

            return response()->json([
                'message' => 'Inscripción eliminada exitosamente'
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error al eliminar inscripción: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
} 