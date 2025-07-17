<?php

namespace App\Application\Course\UseCases;

use App\Application\Course\DTOs\UpdateCourseDTO;
use App\Application\Course\DTOs\CourseResponseDTO;
use App\Application\Course\Services\CourseService;

class UpdateCourseUseCase
{
    public function __construct(
        private CourseService $courseService
    ) {
    }

    public function execute(UpdateCourseDTO $dto): CourseResponseDTO
    {
        return $this->courseService->updateCourse($dto);
    }
} 