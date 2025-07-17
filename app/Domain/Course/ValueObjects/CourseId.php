<?php

namespace App\Domain\Course\ValueObjects;

use InvalidArgumentException;

class CourseId
{
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        // Permitir ID vacío temporalmente para nuevos cursos
        // El ID será asignado por la base de datos
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 