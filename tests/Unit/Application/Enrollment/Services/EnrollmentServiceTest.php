<?php

use App\Application\Enrollment\Services\EnrollmentService;
use App\Application\Enrollment\DTOs\CreateEnrollmentDTO;
use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\Enrollment\ValueObjects\EnrolledAt;
use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;

describe('EnrollmentService', function () {
    
    beforeEach(function () {
        $this->mockRepository = Mockery::mock(EnrollmentRepository::class);
        $this->service = new EnrollmentService($this->mockRepository);
    });

    test('can create enrollment', function () {
        $dto = new CreateEnrollmentDTO('1', '2');
        
        $enrollment = new Enrollment(
            new EnrollmentId('1'),
            new CourseId('2'),
            new StudentId('1'),
            new EnrolledAt('2024-01-15 10:30:00')
        );

        $this->mockRepository->shouldReceive('findByCourseAndStudent')
            ->once()
            ->andReturn(null);

        $this->mockRepository->shouldReceive('save')
            ->once()
            ->andReturn($enrollment);

        $result = $this->service->createEnrollment($dto);

        expect($result->id)->toBe('1');
        expect($result->studentId)->toBe('1');
        expect($result->courseId)->toBe('2');
        expect($result->enrolledAt)->toBe('2024-01-15 10:30:00');
    });

    test('throws exception when creating duplicate enrollment', function () {
        $dto = new CreateEnrollmentDTO('1', '2');
        
        $existingEnrollment = new Enrollment(
            new EnrollmentId('1'),
            new CourseId('2'),
            new StudentId('1'),
            new EnrolledAt('2024-01-15 10:30:00')
        );

        $this->mockRepository->shouldReceive('findByCourseAndStudent')
            ->once()
            ->andReturn($existingEnrollment);

        expect(fn() => $this->service->createEnrollment($dto))
            ->toThrow(InvalidArgumentException::class, 'El estudiante ya está inscrito en este curso');
    });

    test('can get enrollment by id', function () {
        $enrollment = new Enrollment(
            new EnrollmentId('1'),
            new CourseId('2'),
            new StudentId('1'),
            new EnrolledAt('2024-01-15 10:30:00')
        );

        $this->mockRepository->shouldReceive('findById')
            ->once()
            ->andReturn($enrollment);

        $result = $this->service->getEnrollment('1');

        expect($result->id)->toBe('1');
        expect($result->studentId)->toBe('1');
        expect($result->courseId)->toBe('2');
    });

    test('throws exception when enrollment not found', function () {
        $this->mockRepository->shouldReceive('findById')
            ->once()
            ->andReturn(null);

        expect(fn() => $this->service->getEnrollment('999'))
            ->toThrow(InvalidArgumentException::class, 'Inscripción no encontrada');
    });

    test('can get all enrollments', function () {
        $enrollments = [
            new Enrollment(
                new EnrollmentId('1'),
                new CourseId('2'),
                new StudentId('1'),
                new EnrolledAt('2024-01-15 10:30:00')
            ),
            new Enrollment(
                new EnrollmentId('2'),
                new CourseId('3'),
                new StudentId('2'),
                new EnrolledAt('2024-01-16 10:30:00')
            )
        ];

        $this->mockRepository->shouldReceive('findAll')
            ->once()
            ->andReturn($enrollments);

        $result = $this->service->getAllEnrollments();

        expect($result)->toHaveCount(2);
        expect($result[0]->id)->toBe('1');
        expect($result[1]->id)->toBe('2');
    });

    test('can get enrollments by course', function () {
        $enrollments = [
            new Enrollment(
                new EnrollmentId('1'),
                new CourseId('2'),
                new StudentId('1'),
                new EnrolledAt('2024-01-15 10:30:00')
            )
        ];

        $this->mockRepository->shouldReceive('findByCourseId')
            ->once()
            ->andReturn($enrollments);

        $result = $this->service->getEnrollmentsByCourse('2');

        expect($result)->toHaveCount(1);
        expect($result[0]->courseId)->toBe('2');
    });

    test('can get enrollments by student', function () {
        $enrollments = [
            new Enrollment(
                new EnrollmentId('1'),
                new CourseId('2'),
                new StudentId('1'),
                new EnrolledAt('2024-01-15 10:30:00')
            )
        ];

        $this->mockRepository->shouldReceive('findByStudentId')
            ->once()
            ->andReturn($enrollments);

        $result = $this->service->getEnrollmentsByStudent('1');

        expect($result)->toHaveCount(1);
        expect($result[0]->studentId)->toBe('1');
    });

    test('can delete enrollment', function () {
        $enrollment = new Enrollment(
            new EnrollmentId('1'),
            new CourseId('2'),
            new StudentId('1'),
            new EnrolledAt('2024-01-15 10:30:00')
        );

        $this->mockRepository->shouldReceive('findById')
            ->once()
            ->andReturn($enrollment);

        $this->mockRepository->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $result = $this->service->deleteEnrollment('1');

        expect($result)->toBeTrue();
    });

    test('throws exception when trying to delete non-existent enrollment', function () {
        $this->mockRepository->shouldReceive('findById')
            ->once()
            ->andReturn(null);

        expect(fn() => $this->service->deleteEnrollment('999'))
            ->toThrow(InvalidArgumentException::class, 'Inscripción no encontrada');
    });
}); 