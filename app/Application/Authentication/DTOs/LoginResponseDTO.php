<?php

namespace App\Application\Authentication\DTOs;

class LoginResponseDTO
{
    public function __construct(
        public readonly string $token,
        public readonly string $tokenType,
        public readonly int $expiresIn,
        public readonly string $userId,
        public readonly string $userName,
        public readonly string $userEmail
    ) {
    }
} 