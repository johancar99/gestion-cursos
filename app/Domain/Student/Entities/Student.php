<?php

namespace App\Domain\Student\Entities;

use App\Domain\Student\ValueObjects\Email;
use App\Domain\Student\ValueObjects\FirstName;
use App\Domain\Student\ValueObjects\LastName;
use App\Domain\Student\ValueObjects\StudentId;

class Student
{
    private StudentId $id;
    private FirstName $firstName;
    private LastName $lastName;
    private Email $email;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        StudentId $id,
        FirstName $firstName,
        LastName $lastName,
        Email $email,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        FirstName $firstName,
        LastName $lastName,
        Email $email
    ): self {
        $now = new \DateTimeImmutable();
        return new self(
            new StudentId(''), // ID vacío que será asignado por el repositorio
            $firstName,
            $lastName,
            $email,
            $now,
            $now
        );
    }

    public function getId(): StudentId
    {
        return $this->id;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateFirstName(FirstName $firstName): void
    {
        $this->firstName = $firstName;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function updateLastName(LastName $lastName): void
    {
        $this->lastName = $lastName;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function updateEmail(Email $email): void
    {
        $this->email = $email;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function equals(Student $other): bool
    {
        return $this->id->equals($other->id);
    }
} 