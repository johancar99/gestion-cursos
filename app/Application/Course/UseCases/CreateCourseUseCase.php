<?php

namespace App\Application\Course\UseCases;

use App\Application\Course\DTOs\CreateCourseDTO;
use App\Application\Course\DTOs\CourseResponseDTO;
use App\Application\Course\Services\CourseService;

class CreateCourseUseCase
{
    public function __construct(
        private CourseService $courseService
    ) {
    }

    public function execute(CreateCourseDTO $dto): CourseResponseDTO
    {
        return $this->courseService->createCourse($dto);
    }
} 