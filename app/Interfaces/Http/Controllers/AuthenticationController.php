<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Authentication\DTOs\LoginDTO;
use App\Application\Authentication\UseCases\LoginUseCase;
use App\Application\Authentication\UseCases\LogoutUseCase;
use App\Interfaces\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

/**
 * @group Authentication
 * 
 * Endpoints para autenticación de usuarios
 */
class AuthenticationController
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private LogoutUseCase $logoutUseCase
    ) {
    }

    /**
     * Login de usuario
     * 
     * Autentica un usuario con email y contraseña, devolviendo un token de acceso.
     * 
     * @bodyParam email string required El email del usuario. Example: admin@example.com
     * @bodyParam password string required La contraseña del usuario. Example: password123
     * 
     * @response 200 scenario="Login exitoso" {
     *   "message": "Login exitoso",
     *   "data": {
     *     "token": "1|abcdef123456...",
     *     "token_type": "Bearer",
     *     "expires_in": 3600,
     *     "user": {
     *       "id": 1,
     *       "name": "Admin User",
     *       "email": "admin@example.com"
     *     }
     *   }
     * }
     * 
     * @response 401 scenario="Credenciales inválidas" {
     *   "message": "Credenciales inválidas"
     * }
     * 
     * @response 500 scenario="Error interno" {
     *   "message": "Error interno del servidor"
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = new LoginDTO(
                $request->input('email'),
                $request->input('password')
            );

            $response = $this->loginUseCase->execute($dto);

            return response()->json([
                'message' => 'Login exitoso',
                'data' => [
                    'token' => $response->token,
                    'token_type' => $response->tokenType,
                    'expires_in' => $response->expiresIn,
                    'user' => [
                        'id' => $response->userId,
                        'name' => $response->userName,
                        'email' => $response->userEmail,
                    ]
                ]
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Logout de usuario
     * 
     * Invalida el token de acceso actual del usuario autenticado.
     * 
     * @authenticated
     * 
     * @response 200 scenario="Logout exitoso" {
     *   "message": "Logout exitoso"
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
    public function logout(): JsonResponse
    {
        try {
            $this->logoutUseCase->execute();

            return response()->json([
                'message' => 'Logout exitoso'
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
} 