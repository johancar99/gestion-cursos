<?php

namespace App\Domain\Course\Repositories;

use App\Domain\Course\Entities\Course;
use App\Domain\Course\ValueObjects\CourseId;

interface CourseRepository
{
    public function save(Course $course): Course;
    public function findById(CourseId $id): ?Course;
    public function findAll(): array;
    public function findByFilters(array $filters): array;
    public function delete(CourseId $id): bool;
} 