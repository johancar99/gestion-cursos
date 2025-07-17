<?php

namespace App\Application\Student\DTOs;

class CreateStudentDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email
    ) {
    }
} 