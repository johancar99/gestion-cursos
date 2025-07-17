<?php

namespace App\Application\Enrollment\UseCases;

use App\Application\Enrollment\DTOs\EnrollmentResponseDTO;
use App\Application\Enrollment\Services\EnrollmentService;

class GetEnrollmentsByCourseUseCase
{
    public function __construct(
        private EnrollmentService $enrollmentService
    ) {
    }

    public function execute(string $courseId): array
    {
        return $this->enrollmentService->getEnrollmentsByCourse($courseId);
    }
} 