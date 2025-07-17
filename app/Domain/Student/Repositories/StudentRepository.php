<?php

namespace App\Domain\Student\Repositories;

use App\Domain\Student\Entities\Student;
use App\Domain\Student\ValueObjects\Email;
use App\Domain\Student\ValueObjects\StudentId;

interface StudentRepository
{
    public function save(Student $student): void;
    
    public function findById(StudentId $id): ?Student;
    
    public function findByEmail(Email $email): ?Student;
    
    public function existsByEmail(Email $email): bool;
    
    public function delete(StudentId $id): void;
    
    public function getAll(): array;
    
    public function findByFilters(array $filters): array;
} 