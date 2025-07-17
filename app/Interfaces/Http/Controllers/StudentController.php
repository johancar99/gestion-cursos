<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Student\DTOs\CreateStudentDTO;
use App\Application\Student\DTOs\UpdateStudentDTO;
use App\Application\Student\Services\StudentService;
use App\Interfaces\Http\Requests\CreateStudentRequest;
use App\Interfaces\Http\Requests\UpdateStudentRequest;
use App\Interfaces\Http\Resources\StudentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

/**
 * @group Students
 * 
 * Endpoints para gestión de estudiantes del sistema
 */
class StudentController
{
    public function __construct(
        private StudentService $studentService
    ) {
    }

    /**
     * Crear estudiante
     * 
     * Crea un nuevo estudiante en el sistema.
     * 
     * @authenticated
     * 
     * @bodyParam first_name string required El nombre del estudiante. Example: Juan
     * @bodyParam last_name string required El apellido del estudiante. Example: Pérez
     * @bodyParam email string required El email del estudiante. Example: juan.perez@example.com
     * 
     * @response 201 scenario="Estudiante creado" {
     *   "message": "Estudiante creado exitosamente",
     *   "data": {
     *     "id": 1,
     *     "first_name": "Juan",
     *     "last_name": "Pérez",
     *     "email": "juan.perez@example.com",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     * 
     * @response 400 scenario="Datos inválidos" {
     *   "message": "El email ya está registrado"
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
    public function store(CreateStudentRequest $request): JsonResponse
    {
        try {
            $dto = new CreateStudentDTO(
                $request->input('first_name'),
                $request->input('last_name'),
                $request->input('email')
            );

            $studentResponse = $this->studentService->createStudent($dto);

            return response()->json([
                'message' => 'Estudiante creado exitosamente',
                'data' => new StudentResource($studentResponse)
            ], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al crear estudiante: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener estudiantes con filtros
     * 
     * Obtiene una lista paginada de estudiantes con filtros opcionales.
     * 
     * @authenticated
     * 
     * @queryParam first_name string Filtro por nombre del estudiante. Example: Juan
     * @queryParam last_name string Filtro por apellido del estudiante. Example: Pérez
     * @queryParam email string Filtro por email del estudiante. Example: juan.perez@example.com
     * @queryParam name string Filtro por nombre completo. Example: Juan Pérez
     * @queryParam created_at string Filtro por fecha de creación. Example: 2024-01-01
     * @queryParam date_range string Rango de fechas de creación. Example: 2024-01-01,2024-12-31
     * 
     * @response 200 scenario="Lista de estudiantes" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "first_name": "Juan",
     *       "last_name": "Pérez",
     *       "email": "juan.perez@example.com",
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
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
            $filters = $request->only(['first_name', 'last_name', 'email', 'name', 'created_at', 'date_range']);
            
            $studentsResponse = $this->studentService->getStudentsByFilters($filters);

            return response()->json([
                'data' => StudentResource::collection($studentsResponse)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener estudiantes: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener todos los estudiantes
     * 
     * Obtiene una lista completa de todos los estudiantes sin paginación.
     * 
     * @authenticated
     * 
     * @response 200 scenario="Lista completa de estudiantes" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "first_name": "Juan",
     *       "last_name": "Pérez",
     *       "email": "juan.perez@example.com",
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
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
    public function all(): JsonResponse
    {
        try {
            $studentsResponse = $this->studentService->getAllStudents();

            return response()->json([
                'data' => StudentResource::collection($studentsResponse)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener todos los estudiantes: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener estudiante por ID
     * 
     * Obtiene la información detallada de un estudiante específico.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID del estudiante. Example: 1
     * 
     * @response 200 scenario="Estudiante encontrado" {
     *   "data": {
     *     "id": 1,
     *     "first_name": "Juan",
     *     "last_name": "Pérez",
     *     "email": "juan.perez@example.com",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 404 scenario="Estudiante no encontrado" {
     *   "message": "Estudiante no encontrado"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function show(string $id): JsonResponse
    {
        try {
            $studentResponse = $this->studentService->getStudent($id);

            return response()->json([
                'data' => new StudentResource($studentResponse)
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
     * Actualizar estudiante
     * 
     * Actualiza la información de un estudiante existente.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID del estudiante. Example: 1
     * @bodyParam first_name string El nombre del estudiante. Example: Juan Carlos
     * @bodyParam last_name string El apellido del estudiante. Example: Pérez García
     * @bodyParam email string El email del estudiante. Example: juan.carlos@example.com
     * 
     * @response 200 scenario="Estudiante actualizado" {
     *   "message": "Estudiante actualizado exitosamente",
     *   "data": {
     *     "id": 1,
     *     "first_name": "Juan Carlos",
     *     "last_name": "Pérez García",
     *     "email": "juan.carlos@example.com",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     * 
     * @response 400 scenario="Datos inválidos" {
     *   "message": "El email ya está registrado"
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
    public function update(UpdateStudentRequest $request, string $id): JsonResponse
    {
        try {
            $dto = new UpdateStudentDTO(
                $id,
                $request->input('first_name'),
                $request->input('last_name'),
                $request->input('email')
            );

            $studentResponse = $this->studentService->updateStudent($dto);

            return response()->json([
                'message' => 'Estudiante actualizado exitosamente',
                'data' => new StudentResource($studentResponse)
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al actualizar estudiante: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Eliminar estudiante
     * 
     * Elimina un estudiante del sistema.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID del estudiante. Example: 1
     * 
     * @response 200 scenario="Estudiante eliminado" {
     *   "message": "Estudiante eliminado exitosamente"
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 404 scenario="Estudiante no encontrado" {
     *   "message": "Estudiante no encontrado"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->studentService->deleteStudent($id);

            return response()->json([
                'message' => 'Estudiante eliminado exitosamente'
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error al eliminar estudiante: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
} 