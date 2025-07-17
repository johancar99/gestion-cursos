<?php

namespace App\Application\Enrollment\UseCases;

use App\Application\Enrollment\DTOs\EnrollmentResponseDTO;
use App\Application\Enrollment\Services\EnrollmentService;

class GetEnrollmentsByStudentUseCase
{
    public function __construct(
        private EnrollmentService $enrollmentService
    ) {
    }

    public function execute(string $studentId): array
    {
        return $this->enrollmentService->getEnrollmentsByStudent($studentId);
    }
} 