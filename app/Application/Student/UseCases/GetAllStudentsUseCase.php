<?php

namespace App\Application\Student\UseCases;

use App\Application\Student\DTOs\StudentResponseDTO;
use App\Domain\Student\Repositories\StudentRepository;

class GetAllStudentsUseCase
{
    public function __construct(
        private StudentRepository $studentRepository
    ) {
    }

    public function execute(array $filters = []): array
    {
        if (empty($filters)) {
            $students = $this->studentRepository->getAll();
        } else {
            $students = $this->studentRepository->findByFilters($filters);
        }
        
        return array_map(function ($student) {
            return new StudentResponseDTO(
                $student->getId()->value(),
                $student->getFirstName()->value(),
                $student->getLastName()->value(),
                $student->getEmail()->value(),
                $student->getCreatedAt()->format('Y-m-d H:i:s'),
                $student->getUpdatedAt()->format('Y-m-d H:i:s')
            );
        }, $students);
    }
} 