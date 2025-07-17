<?php

namespace App\Domain\Course\ValueObjects;

use InvalidArgumentException;

class EndDate
{
    public function __construct(
        private string $value,
        private ?StartDate $startDate = null
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException('La fecha de fin es obligatoria');
        }

        if (!strtotime($this->value)) {
            throw new InvalidArgumentException('El formato de fecha de fin no es válido');
        }

        $endDate = new \DateTime($this->value);
        $today = new \DateTime();

        // Comparar solo las fechas sin hora
        $endDateOnly = $endDate->format('Y-m-d');
        $todayOnly = $today->format('Y-m-d');

        if ($endDateOnly < $todayOnly) {
            throw new InvalidArgumentException('La fecha de fin no puede ser anterior a hoy');
        }

        // Si tenemos fecha de inicio, validar que la fecha de fin sea posterior
        if ($this->startDate) {
            $startDate = new \DateTime($this->startDate->value());
            $startDateOnly = $startDate->format('Y-m-d');
            
            if ($endDateOnly <= $startDateOnly) {
                throw new InvalidArgumentException('La fecha de fin debe ser posterior a la fecha de inicio');
            }
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