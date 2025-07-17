<?php

namespace App\Application\User\DTOs;

class UpdateUserDTO
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
        public readonly ?string $role = null
    ) {
    }
} 