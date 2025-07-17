<?php

namespace App\Application\Enrollment\DTOs;

class CreateEnrollmentDTO
{
    public function __construct(
        public readonly string $studentId,
        public readonly string $courseId
    ) {
    }
} 