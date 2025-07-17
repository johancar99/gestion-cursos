<?php

namespace App\Application\Course\Services;

use App\Application\Course\DTOs\CreateCourseDTO;
use App\Application\Course\DTOs\UpdateCourseDTO;
use App\Application\Course\DTOs\CourseResponseDTO;
use App\Domain\Course\Entities\Course;
use App\Domain\Course\Repositories\CourseRepository;
use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Course\ValueObjects\Title;
use App\Domain\Course\ValueObjects\Description;
use App\Domain\Course\ValueObjects\StartDate;
use App\Domain\Course\ValueObjects\EndDate;
use InvalidArgumentException;

class CourseService
{
    public function __construct(
        private CourseRepository $courseRepository
    ) {
    }

    public function createCourse(CreateCourseDTO $dto): CourseResponseDTO
    {
        $startDate = new StartDate($dto->startDate);
        $endDate = new EndDate($dto->endDate, $startDate);

        $course = new Course(
            new CourseId(''), // ID temporal, será asignado por la BD
            new Title($dto->title),
            new Description($dto->description),
            $startDate,
            $endDate
        );

        $savedCourse = $this->courseRepository->save($course);

        return new CourseResponseDTO(
            $savedCourse->id()->value(),
            $savedCourse->title()->value(),
            $savedCourse->description()->value(),
            $savedCourse->startDate()->value(),
            $savedCourse->endDate()->value()
        );
    }

    public function getCourse(string $id): CourseResponseDTO
    {
        $course = $this->courseRepository->findById(new CourseId($id));

        if (!$course) {
            throw new InvalidArgumentException('Curso no encontrado');
        }

        return new CourseResponseDTO(
            $course->id()->value(),
            $course->title()->value(),
            $course->description()->value(),
            $course->startDate()->value(),
            $course->endDate()->value()
        );
    }

    public function getAllCourses(): array
    {
        $courses = $this->courseRepository->findAll();

        return array_map(function (Course $course) {
            return new CourseResponseDTO(
                $course->id()->value(),
                $course->title()->value(),
                $course->description()->value(),
                $course->startDate()->value(),
                $course->endDate()->value()
            );
        }, $courses);
    }

    public function getCoursesByFilters(array $filters): array
    {
        $courses = $this->courseRepository->findByFilters($filters);

        return array_map(function (Course $course) {
            return new CourseResponseDTO(
                $course->id()->value(),
                $course->title()->value(),
                $course->description()->value(),
                $course->startDate()->value(),
                $course->endDate()->value()
            );
        }, $courses);
    }

    public function updateCourse(UpdateCourseDTO $dto): CourseResponseDTO
    {
        $course = $this->courseRepository->findById(new CourseId($dto->id));

        if (!$course) {
            throw new InvalidArgumentException('Curso no encontrado');
        }

        $title = $dto->title ? new Title($dto->title) : $course->title();
        $description = $dto->description ? new Description($dto->description) : $course->description();
        $startDate = $dto->startDate ? new StartDate($dto->startDate) : $course->startDate();
        $endDate = $dto->endDate ? new EndDate($dto->endDate, $startDate) : $course->endDate();

        $updatedCourse = new Course(
            $course->id(),
            $title,
            $description,
            $startDate,
            $endDate
        );

        $savedCourse = $this->courseRepository->save($updatedCourse);

        return new CourseResponseDTO(
            $savedCourse->id()->value(),
            $savedCourse->title()->value(),
            $savedCourse->description()->value(),
            $savedCourse->startDate()->value(),
            $savedCourse->endDate()->value()
        );
    }

    public function deleteCourse(string $id): bool
    {
        $course = $this->courseRepository->findById(new CourseId($id));

        if (!$course) {
            throw new InvalidArgumentException('Curso no encontrado');
        }

        return $this->courseRepository->delete(new CourseId($id));
    }
} 