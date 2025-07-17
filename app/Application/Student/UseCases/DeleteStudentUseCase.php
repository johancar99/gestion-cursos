<?php

namespace App\Application\Student\UseCases;

use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Student\ValueObjects\StudentId;
use InvalidArgumentException;

class DeleteStudentUseCase
{
    public function __construct(
        private StudentRepository $studentRepository
    ) {
    }

    public function execute(string $id): void
    {
        $studentId = new StudentId($id);
        $student = $this->studentRepository->findById($studentId);

        if (!$student) {
            throw new InvalidArgumentException('Student not found');
        }

        $this->studentRepository->delete($studentId);
    }
} 