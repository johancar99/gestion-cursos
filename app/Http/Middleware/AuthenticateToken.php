<?php

namespace App\Http\Middleware;

use App\Domain\Authentication\Repositories\AuthenticationTokenRepository;
use App\Domain\Authentication\ValueObjects\TokenValue;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateToken
{
    public function __construct(
        private AuthenticationTokenRepository $tokenRepository
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Token no proporcionado'
            ], 401);
        }

        $tokenValue = new TokenValue($token);
        $authenticationToken = $this->tokenRepository->findByToken($tokenValue);

        if (!$authenticationToken) {
            return response()->json([
                'message' => 'Token inválido'
            ], 401);
        }

        if ($authenticationToken->isExpired()) {
            return response()->json([
                'message' => 'Token expirado'
            ], 401);
        }

        // Agregar el usuario autenticado a la request
        $request->merge(['authenticated_user_id' => $authenticationToken->getUserId()->value()]);

        return $next($request);
    }
} 