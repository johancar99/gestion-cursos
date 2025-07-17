<?php

namespace App\Domain\Enrollment\Repositories;

use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Student\ValueObjects\StudentId;

interface EnrollmentRepository
{
    public function save(Enrollment $enrollment): Enrollment;
    public function findById(EnrollmentId $id): ?Enrollment;
    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?Enrollment;
    public function findAll(): array;
    public function findByCourseId(CourseId $courseId): array;
    public function findByStudentId(StudentId $studentId): array;
    public function delete(EnrollmentId $id): bool;
} 