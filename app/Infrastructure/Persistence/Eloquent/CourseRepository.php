<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Course\Entities\Course;
use App\Domain\Course\Repositories\CourseRepository as CourseRepositoryInterface;
use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Course\ValueObjects\Title;
use App\Domain\Course\ValueObjects\Description;
use App\Domain\Course\ValueObjects\StartDate;
use App\Domain\Course\ValueObjects\EndDate;
use App\Models\Course as CourseModel;
use Illuminate\Support\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function save(Course $course): Course
    {
        // Si el ID está vacío, es una creación nueva
        if (empty($course->id()->value())) {
            $courseModel = CourseModel::create([
                'title' => $course->title()->value(),
                'description' => $course->description()->value(),
                'start_date' => $course->startDate()->value(),
                'end_date' => $course->endDate()->value(),
            ]);
        } else {
            // Es una actualización
            $courseModel = CourseModel::updateOrCreate(
                ['id' => $course->id()->value()],
                [
                    'title' => $course->title()->value(),
                    'description' => $course->description()->value(),
                    'start_date' => $course->startDate()->value(),
                    'end_date' => $course->endDate()->value(),
                ]
            );
        }

        return new Course(
            new CourseId($courseModel->id),
            new Title($courseModel->title),
            new Description($courseModel->description),
            new StartDate($courseModel->start_date),
            new EndDate($courseModel->end_date)
        );
    }

    public function findById(CourseId $id): ?Course
    {
        $courseModel = CourseModel::find($id->value());

        if (!$courseModel) {
            return null;
        }

        return new Course(
            new CourseId($courseModel->id),
            new Title($courseModel->title),
            new Description($courseModel->description),
            new StartDate($courseModel->start_date),
            new EndDate($courseModel->end_date)
        );
    }

    public function findAll(): array
    {
        return CourseModel::all()->map(function ($courseModel) {
            return new Course(
                new CourseId($courseModel->id),
                new Title($courseModel->title),
                new Description($courseModel->description),
                new StartDate($courseModel->start_date),
                new EndDate($courseModel->end_date)
            );
        })->toArray();
    }

    public function findByFilters(array $filters): array
    {
        $query = CourseModel::query();

        if (isset($filters['title'])) {
            $query->where('title', 'LIKE', '%' . $filters['title'] . '%');
        }

        if (isset($filters['description'])) {
            $query->where('description', 'LIKE', '%' . $filters['description'] . '%');
        }

        if (isset($filters['start_date'])) {
            $query->where('start_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('end_date', '<=', $filters['end_date']);
        }

        if (isset($filters['date_range'])) {
            $query->whereBetween('start_date', [$filters['date_range']['start'], $filters['date_range']['end']]);
        }

        return $query->get()->map(function ($courseModel) {
            return new Course(
                new CourseId($courseModel->id),
                new Title($courseModel->title),
                new Description($courseModel->description),
                new StartDate($courseModel->start_date),
                new EndDate($courseModel->end_date)
            );
        })->toArray();
    }

    public function delete(CourseId $id): bool
    {
        $courseModel = CourseModel::find($id->value());

        if (!$courseModel) {
            return false;
        }

        return $courseModel->delete();
    }
} 