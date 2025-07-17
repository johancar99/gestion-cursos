<?php

namespace App\Domain\Course\ValueObjects;

use InvalidArgumentException;

class StartDate
{
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException('La fecha de inicio es obligatoria');
        }

        if (!strtotime($this->value)) {
            throw new InvalidArgumentException('El formato de fecha de inicio no es válido');
        }

        $date = new \DateTime($this->value);
        $today = new \DateTime();
        
        // Comparar solo las fechas sin hora
        $dateOnly = $date->format('Y-m-d');
        $todayOnly = $today->format('Y-m-d');

        if ($dateOnly < $todayOnly) {
            throw new InvalidArgumentException('La fecha de inicio no puede ser anterior a hoy');
        }
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