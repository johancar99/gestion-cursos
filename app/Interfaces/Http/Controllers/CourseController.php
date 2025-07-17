<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Course\DTOs\CreateCourseDTO;
use App\Application\Course\DTOs\UpdateCourseDTO;
use App\Application\Course\Services\CourseService;
use App\Interfaces\Http\Requests\CreateCourseRequest;
use App\Interfaces\Http\Requests\UpdateCourseRequest;
use App\Interfaces\Http\Resources\CourseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

/**
 * @group Courses
 * 
 * Endpoints para gestión de cursos del sistema
 */
class CourseController
{
    public function __construct(
        private CourseService $courseService
    ) {
    }

    /**
     * Crear curso
     * 
     * Crea un nuevo curso en el sistema.
     * 
     * @authenticated
     * 
     * @bodyParam title string required El título del curso. Example: Programación Web
     * @bodyParam description string required La descripción del curso. Example: Curso completo de programación web
     * @bodyParam start_date string required La fecha de inicio del curso. Example: 2024-02-01
     * @bodyParam end_date string required La fecha de fin del curso. Example: 2024-06-30
     * 
     * @response 201 scenario="Curso creado" {
     *   "message": "Curso creado exitosamente",
     *   "data": {
     *     "id": 1,
     *     "title": "Programación Web",
     *     "description": "Curso completo de programación web",
     *     "start_date": "2024-02-01",
     *     "end_date": "2024-06-30",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     * 
     * @response 400 scenario="Datos inválidos" {
     *   "message": "La fecha de fin debe ser posterior a la fecha de inicio"
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
    public function store(CreateCourseRequest $request): JsonResponse
    {
        try {
            $dto = new CreateCourseDTO(
                $request->input('title'),
                $request->input('description'),
                $request->input('start_date'),
                $request->input('end_date')
            );

            $courseResponse = $this->courseService->createCourse($dto);

            return response()->json([
                'message' => 'Curso creado exitosamente',
                'data' => new CourseResource($courseResponse)
            ], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al crear curso: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener cursos con filtros
     * 
     * Obtiene una lista paginada de cursos con filtros opcionales.
     * 
     * @authenticated
     * 
     * @queryParam title string Filtro por título del curso. Example: Programación
     * @queryParam description string Filtro por descripción del curso. Example: web
     * @queryParam start_date string Filtro por fecha de inicio. Example: 2024-02-01
     * @queryParam end_date string Filtro por fecha de fin. Example: 2024-06-30
     * @queryParam date_range string Rango de fechas. Example: 2024-02-01,2024-06-30
     * 
     * @response 200 scenario="Lista de cursos" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Programación Web",
     *       "description": "Curso completo de programación web",
     *       "start_date": "2024-02-01",
     *       "end_date": "2024-06-30",
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
            $filters = $request->only(['title', 'description', 'start_date', 'end_date', 'date_range']);
            
            $courses = $this->courseService->getCoursesByFilters($filters);

            return response()->json([
                'data' => CourseResource::collection($courses)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener cursos: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener todos los cursos
     * 
     * Obtiene una lista completa de todos los cursos sin paginación.
     * 
     * @authenticated
     * 
     * @response 200 scenario="Lista completa de cursos" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Programación Web",
     *       "description": "Curso completo de programación web",
     *       "start_date": "2024-02-01",
     *       "end_date": "2024-06-30",
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
            $courses = $this->courseService->getAllCourses();

            return response()->json([
                'data' => CourseResource::collection($courses)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener todos los cursos: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener curso por ID
     * 
     * Obtiene la información detallada de un curso específico.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID del curso. Example: 1
     * 
     * @response 200 scenario="Curso encontrado" {
     *   "data": {
     *     "id": 1,
     *     "title": "Programación Web",
     *     "description": "Curso completo de programación web",
     *     "start_date": "2024-02-01",
     *     "end_date": "2024-06-30",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 404 scenario="Curso no encontrado" {
     *   "message": "Curso no encontrado"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function show(string $id): JsonResponse
    {
        try {
            $courseResponse = $this->courseService->getCourse($id);

            return response()->json([
                'data' => new CourseResource($courseResponse)
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
     * Actualizar curso
     * 
     * Actualiza la información de un curso existente.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID del curso. Example: 1
     * @bodyParam title string El título del curso. Example: Programación Web Avanzada
     * @bodyParam description string La descripción del curso. Example: Curso avanzado de programación web
     * @bodyParam start_date string La fecha de inicio del curso. Example: 2024-03-01
     * @bodyParam end_date string La fecha de fin del curso. Example: 2024-07-30
     * 
     * @response 200 scenario="Curso actualizado" {
     *   "message": "Curso actualizado exitosamente",
     *   "data": {
     *     "id": 1,
     *     "title": "Programación Web Avanzada",
     *     "description": "Curso avanzado de programación web",
     *     "start_date": "2024-03-01",
     *     "end_date": "2024-07-30",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     * 
     * @response 400 scenario="Datos inválidos" {
     *   "message": "La fecha de fin debe ser posterior a la fecha de inicio"
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
    public function update(UpdateCourseRequest $request, string $id): JsonResponse
    {
        try {
            $dto = new UpdateCourseDTO(
                $id,
                $request->input('title'),
                $request->input('description'),
                $request->input('start_date'),
                $request->input('end_date')
            );

            $courseResponse = $this->courseService->updateCourse($dto);

            return response()->json([
                'message' => 'Curso actualizado exitosamente',
                'data' => new CourseResource($courseResponse)
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al actualizar curso: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Eliminar curso
     * 
     * Elimina un curso del sistema.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID del curso. Example: 1
     * 
     * @response 200 scenario="Curso eliminado" {
     *   "message": "Curso eliminado exitosamente"
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 404 scenario="Curso no encontrado" {
     *   "message": "Curso no encontrado"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->courseService->deleteCourse($id);

            return response()->json([
                'message' => 'Curso eliminado exitosamente'
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error al eliminar curso: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
} 