<?php

namespace App\Application\Enrollment\UseCases;

use App\Application\Enrollment\DTOs\EnrollmentResponseDTO;
use App\Application\Enrollment\Services\EnrollmentService;

class GetEnrollmentUseCase
{
    public function __construct(
        private EnrollmentService $enrollmentService
    ) {
    }

    public function execute(string $id): EnrollmentResponseDTO
    {
        return $this->enrollmentService->getEnrollment($id);
    }
} 