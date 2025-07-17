<?php

namespace App\Application\User\DTOs;

class UserResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $createdAt,
        public readonly string $updatedAt
    ) {
    }
} 