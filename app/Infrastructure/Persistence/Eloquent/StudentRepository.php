<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Student\Entities\Student;
use App\Domain\Student\Repositories\StudentRepository as StudentRepositoryInterface;
use App\Domain\Student\ValueObjects\Email;
use App\Domain\Student\ValueObjects\FirstName;
use App\Domain\Student\ValueObjects\LastName;
use App\Domain\Student\ValueObjects\StudentId;
use App\Models\Student as StudentEloquentModel;

class StudentRepository implements StudentRepositoryInterface
{
    public function save(Student $student): void
    {
        $model = new StudentEloquentModel();
        
        if ($student->getId()->value() !== '') {
            $model = StudentEloquentModel::find($student->getId()->value());
            if (!$model) {
                throw new \InvalidArgumentException('Student not found');
            }
        }
        
        $model->first_name = $student->getFirstName()->value();
        $model->last_name = $student->getLastName()->value();
        $model->email = $student->getEmail()->value();
        $model->created_at = $student->getCreatedAt();
        $model->updated_at = $student->getUpdatedAt();
        
        $model->save();
    }
    
    public function findById(StudentId $id): ?Student
    {
        $model = StudentEloquentModel::find($id->value());
        
        if (!$model) {
            return null;
        }
        
        return new Student(
            new StudentId($model->id),
            new FirstName($model->first_name),
            new LastName($model->last_name),
            new Email($model->email),
            $model->created_at->toDateTimeImmutable(),
            $model->updated_at->toDateTimeImmutable()
        );
    }
    
    public function findByEmail(Email $email): ?Student
    {
        $model = StudentEloquentModel::where('email', $email->value())->first();
        
        if (!$model) {
            return null;
        }
        
        return new Student(
            new StudentId($model->id),
            new FirstName($model->first_name),
            new LastName($model->last_name),
            new Email($model->email),
            $model->created_at->toDateTimeImmutable(),
            $model->updated_at->toDateTimeImmutable()
        );
    }
    
    public function existsByEmail(Email $email): bool
    {
        return StudentEloquentModel::where('email', $email->value())->exists();
    }
    
    public function delete(StudentId $id): void
    {
        $model = StudentEloquentModel::find($id->value());
        
        if ($model) {
            $model->delete();
        }
    }
    
    public function getAll(): array
    {
        $models = StudentEloquentModel::all();
        
        return $models->map(function ($model) {
            return new Student(
                new StudentId($model->id),
                new FirstName($model->first_name),
                new LastName($model->last_name),
                new Email($model->email),
                $model->created_at->toDateTimeImmutable(),
                $model->updated_at->toDateTimeImmutable()
            );
        })->toArray();
    }

    public function findByFilters(array $filters): array
    {
        $query = StudentEloquentModel::query();

        if (isset($filters['first_name'])) {
            $query->where('first_name', 'LIKE', '%' . $filters['first_name'] . '%');
        }

        if (isset($filters['last_name'])) {
            $query->where('last_name', 'LIKE', '%' . $filters['last_name'] . '%');
        }

        if (isset($filters['email'])) {
            $query->where('email', 'LIKE', '%' . $filters['email'] . '%');
        }

        if (isset($filters['name'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'LIKE', '%' . $filters['name'] . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $filters['name'] . '%');
            });
        }

        if (isset($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        if (isset($filters['date_range'])) {
            $query->whereBetween('created_at', [$filters['date_range']['start'], $filters['date_range']['end']]);
        }

        $models = $query->get();

        return $models->map(function ($model) {
            return new Student(
                new StudentId($model->id),
                new FirstName($model->first_name),
                new LastName($model->last_name),
                new Email($model->email),
                $model->created_at->toDateTimeImmutable(),
                $model->updated_at->toDateTimeImmutable()
            );
        })->toArray();
    }
} 