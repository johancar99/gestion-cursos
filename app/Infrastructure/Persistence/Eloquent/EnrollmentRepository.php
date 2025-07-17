<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Repositories\EnrollmentRepository as EnrollmentRepositoryInterface;
use App\Domain\Enrollment\ValueObjects\EnrollmentId;
use App\Domain\Enrollment\ValueObjects\EnrolledAt;
use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Student\ValueObjects\StudentId;
use App\Models\Enrollment as EnrollmentModel;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function save(Enrollment $enrollment): Enrollment
    {
        $model = new EnrollmentModel();
        
        if ($enrollment->id()->value() !== '') {
            $model = EnrollmentModel::find($enrollment->id()->value());
            if (!$model) {
                throw new \InvalidArgumentException('Inscripción no encontrada');
            }
        }

        $model->course_id = $enrollment->courseId()->value();
        $model->student_id = $enrollment->studentId()->value();
        $model->enrolled_at = $enrollment->enrolledAt()->value();
        $model->save();

        return new Enrollment(
            new EnrollmentId($model->id),
            new CourseId($model->course_id),
            new StudentId($model->student_id),
            new EnrolledAt($model->enrolled_at->format('Y-m-d H:i:s'))
        );
    }

    public function findById(EnrollmentId $id): ?Enrollment
    {
        $model = EnrollmentModel::find($id->value());

        if (!$model) {
            return null;
        }

        return new Enrollment(
            new EnrollmentId($model->id),
            new CourseId($model->course_id),
            new StudentId($model->student_id),
            new EnrolledAt($model->enrolled_at->format('Y-m-d H:i:s'))
        );
    }

    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?Enrollment
    {
        $model = EnrollmentModel::where('course_id', $courseId->value())
            ->where('student_id', $studentId->value())
            ->first();

        if (!$model) {
            return null;
        }

        return new Enrollment(
            new EnrollmentId($model->id),
            new CourseId($model->course_id),
            new StudentId($model->student_id),
            new EnrolledAt($model->enrolled_at->format('Y-m-d H:i:s'))
        );
    }

    public function findAll(): array
    {
        $models = EnrollmentModel::all();

        return array_map(function (EnrollmentModel $model) {
            return new Enrollment(
                new EnrollmentId($model->id),
                new CourseId($model->course_id),
                new StudentId($model->student_id),
                new EnrolledAt($model->enrolled_at->format('Y-m-d H:i:s'))
            );
        }, $models->all());
    }

    public function findByCourseId(CourseId $courseId): array
    {
        $models = EnrollmentModel::where('course_id', $courseId->value())->get();

        return array_map(function (EnrollmentModel $model) {
            return new Enrollment(
                new EnrollmentId($model->id),
                new CourseId($model->course_id),
                new StudentId($model->student_id),
                new EnrolledAt($model->enrolled_at->format('Y-m-d H:i:s'))
            );
        }, $models->all());
    }

    public function findByStudentId(StudentId $studentId): array
    {
        $models = EnrollmentModel::where('student_id', $studentId->value())->get();

        return array_map(function (EnrollmentModel $model) {
            return new Enrollment(
                new EnrollmentId($model->id),
                new CourseId($model->course_id),
                new StudentId($model->student_id),
                new EnrolledAt($model->enrolled_at->format('Y-m-d H:i:s'))
            );
        }, $models->all());
    }

    public function delete(EnrollmentId $id): bool
    {
        $model = EnrollmentModel::find($id->value());

        if (!$model) {
            return false;
        }

        return $model->delete();
    }
} 