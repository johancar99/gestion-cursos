<?php

namespace App\Application\Enrollment\DTOs;

class EnrollmentResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $studentId,
        public readonly string $courseId,
        public readonly string $enrolledAt
    ) {
    }
} 