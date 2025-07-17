<?php

namespace App\Application\Authentication\UseCases;

use App\Models\User;
use Illuminate\Http\Request;
use InvalidArgumentException;

class LogoutUseCase
{
    public function __construct(
        private Request $request
    ) {
    }

    public function execute(): void
    {
        $user = $this->request->user();
        
        if (!$user) {
            throw new InvalidArgumentException('User not authenticated');
        }

        // Revocar todos los tokens del usuario
        $user->tokens()->delete();
    }
} 