<?php

namespace App\Application\Enrollment\UseCases;

use App\Application\Enrollment\DTOs\EnrollmentResponseDTO;
use App\Application\Enrollment\Services\EnrollmentService;

class GetAllEnrollmentsUseCase
{
    public function __construct(
        private EnrollmentService $enrollmentService
    ) {
    }

    public function execute(): array
    {
        return $this->enrollmentService->getAllEnrollments();
    }
} 