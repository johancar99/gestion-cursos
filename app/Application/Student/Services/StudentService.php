<?php

namespace App\Application\Student\Services;

use App\Application\Student\DTOs\CreateStudentDTO;
use App\Application\Student\DTOs\StudentResponseDTO;
use App\Application\Student\DTOs\UpdateStudentDTO;
use App\Application\Student\UseCases\CreateStudentUseCase;
use App\Application\Student\UseCases\DeleteStudentUseCase;
use App\Application\Student\UseCases\GetAllStudentsUseCase;
use App\Application\Student\UseCases\GetStudentUseCase;
use App\Application\Student\UseCases\UpdateStudentUseCase;

class StudentService
{
    public function __construct(
        private CreateStudentUseCase $createStudentUseCase,
        private GetStudentUseCase $getStudentUseCase,
        private GetAllStudentsUseCase $getAllStudentsUseCase,
        private UpdateStudentUseCase $updateStudentUseCase,
        private DeleteStudentUseCase $deleteStudentUseCase
    ) {
    }

    public function createStudent(CreateStudentDTO $dto): StudentResponseDTO
    {
        return $this->createStudentUseCase->execute($dto);
    }

    public function getStudent(string $id): StudentResponseDTO
    {
        return $this->getStudentUseCase->execute($id);
    }

    public function getAllStudents(): array
    {
        return $this->getAllStudentsUseCase->execute();
    }

    public function getStudentsByFilters(array $filters): array
    {
        return $this->getAllStudentsUseCase->execute($filters);
    }

    public function updateStudent(UpdateStudentDTO $dto): StudentResponseDTO
    {
        return $this->updateStudentUseCase->execute($dto);
    }

    public function deleteStudent(string $id): void
    {
        $this->deleteStudentUseCase->execute($id);
    }
} 