<?php

namespace App\Application\Authentication\DTOs;

class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {
    }
} 