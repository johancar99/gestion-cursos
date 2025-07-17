<?php

namespace App\Application\Course\UseCases;

use App\Application\Course\DTOs\CourseResponseDTO;
use App\Application\Course\Services\CourseService;

class GetAllCoursesUseCase
{
    public function __construct(
        private CourseService $courseService
    ) {
    }

    public function execute(): array
    {
        return $this->courseService->getAllCourses();
    }
} 