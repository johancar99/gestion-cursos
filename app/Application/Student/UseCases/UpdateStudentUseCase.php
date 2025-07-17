<?php

namespace App\Application\Student\UseCases;

use App\Application\Student\DTOs\StudentResponseDTO;
use App\Application\Student\DTOs\UpdateStudentDTO;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Student\ValueObjects\Email;
use App\Domain\Student\ValueObjects\FirstName;
use App\Domain\Student\ValueObjects\LastName;
use App\Domain\Student\ValueObjects\StudentId;
use InvalidArgumentException;

class UpdateStudentUseCase
{
    public function __construct(
        private StudentRepository $studentRepository
    ) {
    }

    public function execute(UpdateStudentDTO $dto): StudentResponseDTO
    {
        $studentId = new StudentId($dto->id);
        $student = $this->studentRepository->findById($studentId);

        if (!$student) {
            throw new InvalidArgumentException('Student not found');
        }

        // Validar que el email no exista en otro estudiante
        $email = new Email($dto->email);
        $existingStudent = $this->studentRepository->findByEmail($email);
        if ($existingStudent && !$existingStudent->equals($student)) {
            throw new InvalidArgumentException('Email already exists');
        }

        // Crear value objects
        $firstName = new FirstName($dto->firstName);
        $lastName = new LastName($dto->lastName);

        // Actualizar la entidad Student
        $student->updateFirstName($firstName);
        $student->updateLastName($lastName);
        $student->updateEmail($email);

        // Guardar en el repositorio
        $this->studentRepository->save($student);

        // Retornar DTO de respuesta
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