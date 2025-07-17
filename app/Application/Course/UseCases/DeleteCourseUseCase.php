<?php

namespace App\Application\Course\UseCases;

use App\Application\Course\Services\CourseService;

class DeleteCourseUseCase
{
    public function __construct(
        private CourseService $courseService
    ) {
    }

    public function execute(string $id): bool
    {
        return $this->courseService->deleteCourse($id);
    }
} 