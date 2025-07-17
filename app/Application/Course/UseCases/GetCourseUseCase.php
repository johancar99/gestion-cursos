<?php

namespace App\Application\Course\UseCases;

use App\Application\Course\DTOs\CourseResponseDTO;
use App\Application\Course\Services\CourseService;

class GetCourseUseCase
{
    public function __construct(
        private CourseService $courseService
    ) {
    }

    public function execute(string $id): CourseResponseDTO
    {
        return $this->courseService->getCourse($id);
    }
} 