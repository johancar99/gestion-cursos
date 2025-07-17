<?php

namespace App\Application\Enrollment\UseCases;

use App\Application\Enrollment\Services\EnrollmentService;

class DeleteEnrollmentUseCase
{
    public function __construct(
        private EnrollmentService $enrollmentService
    ) {
    }

    public function execute(string $id): bool
    {
        return $this->enrollmentService->deleteEnrollment($id);
    }
} 