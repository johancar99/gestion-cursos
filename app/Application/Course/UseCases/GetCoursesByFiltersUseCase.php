<?php

namespace App\Application\Course\UseCases;

use App\Application\Course\DTOs\CourseResponseDTO;
use App\Application\Course\Services\CourseService;

class GetCoursesByFiltersUseCase
{
    public function __construct(
        private CourseService $courseService
    ) {
    }

    public function execute(array $filters): array
    {
        return $this->courseService->getCoursesByFilters($filters);
    }
} 