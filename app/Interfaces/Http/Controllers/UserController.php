<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\UpdateUserDTO;
use App\Application\User\Services\UserService;
use App\Interfaces\Http\Requests\CreateUserRequest;
use App\Interfaces\Http\Requests\UpdateUserRequest;
use App\Interfaces\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

/**
 * @group Users
 * 
 * Endpoints para gestión de usuarios del sistema
 */
class UserController
{
    public function __construct(
        private UserService $userService
    ) {
    }

    /**
     * Obtener usuarios con filtros
     * 
     * Obtiene una lista paginada de usuarios con filtros opcionales.
     * 
     * @authenticated
     * 
     * @queryParam name string Filtro por nombre del usuario. Example: admin
     * @queryParam email string Filtro por email del usuario. Example: admin@example.com
     * @queryParam created_at string Filtro por fecha de creación. Example: 2024-01-01
     * @queryParam date_range string Rango de fechas de creación. Example: 2024-01-01,2024-12-31
     * 
     * @response 200 scenario="Lista de usuarios" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Admin User",
     *       "email": "admin@example.com",
     *       "role": "admin",
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
            $filters = $request->only(['name', 'email', 'created_at', 'date_range']);
            
            $users = $this->userService->getUsersByFilters($filters);

            return response()->json([
                'data' => UserResource::collection($users)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener todos los usuarios
     * 
     * Obtiene una lista completa de todos los usuarios sin paginación.
     * 
     * @authenticated
     * 
     * @response 200 scenario="Lista completa de usuarios" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Admin User",
     *       "email": "admin@example.com",
     *       "role": "admin",
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
            $users = $this->userService->getAllUsers();

            return response()->json([
                'data' => UserResource::collection($users)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener todos los usuarios: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Crear usuario
     * 
     * Crea un nuevo usuario en el sistema.
     * 
     * @authenticated
     * 
     * @bodyParam name string required El nombre del usuario. Example: John Doe
     * @bodyParam email string required El email del usuario. Example: john@example.com
     * @bodyParam password string required La contraseña del usuario. Example: password123
     * @bodyParam role string required El rol del usuario. Example: admin
     * 
     * @response 201 scenario="Usuario creado" {
     *   "message": "Usuario creado exitosamente",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "role": "admin",
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
    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $dto = new CreateUserDTO(
                $request->input('name'),
                $request->input('email'),
                $request->input('password'),
                $request->input('role')
            );

            $userResponse = $this->userService->createUser($dto);

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'data' => new UserResource($userResponse)
            ], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener usuario por ID
     * 
     * Obtiene la información detallada de un usuario específico.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID del usuario. Example: 1
     * 
     * @response 200 scenario="Usuario encontrado" {
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "role": "admin",
     *     "created_at": "2024-01-01T00:00:00.000000Z",
     *     "updated_at": "2024-01-01T00:00:00.000000Z"
     *   }
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 404 scenario="Usuario no encontrado" {
     *   "message": "Usuario no encontrado"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function show(string $id): JsonResponse
    {
        try {
            $userResponse = $this->userService->getUser($id);

            return response()->json([
                'data' => new UserResource($userResponse)
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
     * Actualizar usuario
     * 
     * Actualiza la información de un usuario existente.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID del usuario. Example: 1
     * @bodyParam name string El nombre del usuario. Example: John Doe Updated
     * @bodyParam email string El email del usuario. Example: john.updated@example.com
     * @bodyParam password string La nueva contraseña del usuario. Example: newpassword123
     * @bodyParam role string El rol del usuario. Example: user
     * 
     * @response 200 scenario="Usuario actualizado" {
     *   "message": "Usuario actualizado exitosamente",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe Updated",
     *     "email": "john.updated@example.com",
     *     "role": "user",
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
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        try {
            $dto = new UpdateUserDTO(
                $id,
                $request->input('name'),
                $request->input('email'),
                $request->input('password'),
                $request->input('role')
            );

            $userResponse = $this->userService->updateUser($dto);

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'data' => new UserResource($userResponse)
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Eliminar usuario
     * 
     * Elimina un usuario del sistema.
     * 
     * @authenticated
     * 
     * @urlParam id string required El ID del usuario. Example: 1
     * 
     * @response 200 scenario="Usuario eliminado" {
     *   "message": "Usuario eliminado exitosamente"
     * }
     * 
     * @response 401 scenario="No autenticado" {
     *   "message": "Unauthenticated"
     * }
     * 
     * @response 404 scenario="Usuario no encontrado" {
     *   "message": "Usuario no encontrado"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->userService->deleteUser($id);

            return response()->json([
                'message' => 'Usuario eliminado exitosamente'
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
} 