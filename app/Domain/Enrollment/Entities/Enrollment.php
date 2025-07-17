<?php

namespace App\Domain\Enrollment\Entities;

use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\Enrollment\ValueObjects\EnrolledAt;
use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Student\ValueObjects\StudentId;

class Enrollment
{
    public function __construct(
        private EnrollmentId $id,
        private CourseId $courseId,
        private StudentId $studentId,
        private EnrolledAt $enrolledAt
    ) {
    }

    public function id(): EnrollmentId
    {
        return $this->id;
    }

    public function courseId(): CourseId
    {
        return $this->courseId;
    }

    public function studentId(): StudentId
    {
        return $this->studentId;
    }

    public function enrolledAt(): EnrolledAt
    {
        return $this->enrolledAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'course_id' => $this->courseId->value(),
            'student_id' => $this->studentId->value(),
            'enrolled_at' => $this->enrolledAt->toString(),
        ];
    }
} 