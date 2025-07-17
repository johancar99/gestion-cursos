<?php

namespace App\Application\Student\UseCases;

use App\Application\Student\DTOs\CreateStudentDTO;
use App\Application\Student\DTOs\StudentResponseDTO;
use App\Domain\Student\Entities\Student;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Student\ValueObjects\Email;
use App\Domain\Student\ValueObjects\FirstName;
use App\Domain\Student\ValueObjects\LastName;
use App\Domain\Student\ValueObjects\StudentId;
use InvalidArgumentException;

class CreateStudentUseCase
{
    public function __construct(
        private StudentRepository $studentRepository
    ) {
    }

    public function execute(CreateStudentDTO $dto): StudentResponseDTO
    {
        // Validar que el email no exista
        $email = new Email($dto->email);
        if ($this->studentRepository->existsByEmail($email)) {
            throw new InvalidArgumentException('Email already exists');
        }

        // Crear value objects
        $firstName = new FirstName($dto->firstName);
        $lastName = new LastName($dto->lastName);

        // Crear la entidad Student
        $student = Student::create($firstName, $lastName, $email);

        // Guardar en el repositorio
        $this->studentRepository->save($student);

        // Buscar el estudiante guardado para obtener el ID generado
        $savedStudent = $this->studentRepository->findByEmail($email);
        
        if (!$savedStudent) {
            throw new InvalidArgumentException('Failed to save student');
        }

        // Retornar DTO de respuesta
        return new StudentResponseDTO(
            $savedStudent->getId()->value(),
            $savedStudent->getFirstName()->value(),
            $savedStudent->getLastName()->value(),
            $savedStudent->getEmail()->value(),
            $savedStudent->getCreatedAt()->format('Y-m-d H:i:s'),
            $savedStudent->getUpdatedAt()->format('Y-m-d H:i:s')
        );
    }
} 