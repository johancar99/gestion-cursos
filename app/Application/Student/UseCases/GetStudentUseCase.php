<?php

namespace App\Application\Student\UseCases;

use App\Application\Student\DTOs\StudentResponseDTO;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Student\ValueObjects\StudentId;
use InvalidArgumentException;

class GetStudentUseCase
{
    public function __construct(
        private StudentRepository $studentRepository
    ) {
    }

    public function execute(string $id): StudentResponseDTO
    {
        $studentId = new StudentId($id);
        $student = $this->studentRepository->findById($studentId);

        if (!$student) {
            throw new InvalidArgumentException('Student not found');
        }

        return new StudentResponseDTO(
            $student->getId()->value(),
            $student->getFirstName()->value(),
            $student->getLastName()->value(),
            $student->getEmail()->value(),
            $student->getCreatedAt()->format('Y-m-d H:i:s'),
            $student->getUpdatedAt()->format('Y-m-d H:i:s')
        );
    }
} 