<?php

namespace App\Application\Enrollment\Services;

use App\Application\Enrollment\DTOs\CreateEnrollmentDTO;
use App\Application\Enrollment\DTOs\EnrollmentResponseDTO;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\Enrollment\ValueObjects\EnrolledAt;
use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Student\ValueObjects\StudentId;
use InvalidArgumentException;

class EnrollmentService
{
    public function __construct(
        private EnrollmentRepository $enrollmentRepository
    ) {
    }

    public function createEnrollment(CreateEnrollmentDTO $dto): EnrollmentResponseDTO
    {
        // Verificar si ya existe una inscripción para este estudiante y curso
        $existingEnrollment = $this->enrollmentRepository->findByCourseAndStudent(
            new CourseId($dto->courseId),
            new StudentId($dto->studentId)
        );

        if ($existingEnrollment) {
            throw new InvalidArgumentException('El estudiante ya está inscrito en este curso');
        }

        $enrollment = new Enrollment(
            new EnrollmentId(''), // ID temporal, será asignado por la BD
            new CourseId($dto->courseId),
            new StudentId($dto->studentId),
            EnrolledAt::now()
        );

        $savedEnrollment = $this->enrollmentRepository->save($enrollment);

        return new EnrollmentResponseDTO(
            $savedEnrollment->id()->value(),
            $savedEnrollment->studentId()->value(),
            $savedEnrollment->courseId()->value(),
            $savedEnrollment->enrolledAt()->toString()
        );
    }

    public function getEnrollment(string $id): EnrollmentResponseDTO
    {
        $enrollment = $this->enrollmentRepository->findById(new EnrollmentId($id));

        if (!$enrollment) {
            throw new InvalidArgumentException('Inscripción no encontrada');
        }

        return new EnrollmentResponseDTO(
            $enrollment->id()->value(),
            $enrollment->studentId()->value(),
            $enrollment->courseId()->value(),
            $enrollment->enrolledAt()->toString()
        );
    }

    public function getAllEnrollments(): array
    {
        $enrollments = $this->enrollmentRepository->findAll();

        return array_map(function (Enrollment $enrollment) {
            return new EnrollmentResponseDTO(
                $enrollment->id()->value(),
                $enrollment->studentId()->value(),
                $enrollment->courseId()->value(),
                $enrollment->enrolledAt()->toString()
            );
        }, $enrollments);
    }

    public function getEnrollmentsByCourse(string $courseId): array
    {
        $enrollments = $this->enrollmentRepository->findByCourseId(new CourseId($courseId));

        return array_map(function (Enrollment $enrollment) {
            return new EnrollmentResponseDTO(
                $enrollment->id()->value(),
                $enrollment->studentId()->value(),
                $enrollment->courseId()->value(),
                $enrollment->enrolledAt()->toString()
            );
        }, $enrollments);
    }

    public function getEnrollmentsByStudent(string $studentId): array
    {
        $enrollments = $this->enrollmentRepository->findByStudentId(new StudentId($studentId));

        return array_map(function (Enrollment $enrollment) {
            return new EnrollmentResponseDTO(
                $enrollment->id()->value(),
                $enrollment->studentId()->value(),
                $enrollment->courseId()->value(),
                $enrollment->enrolledAt()->toString()
            );
        }, $enrollments);
    }

    public function deleteEnrollment(string $id): bool
    {
        $enrollment = $this->enrollmentRepository->findById(new EnrollmentId($id));

        if (!$enrollment) {
            throw new InvalidArgumentException('Inscripción no encontrada');
        }

        return $this->enrollmentRepository->delete(new EnrollmentId($id));
    }
} 