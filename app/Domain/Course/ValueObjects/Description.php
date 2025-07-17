<?php

namespace App\Domain\Course\ValueObjects;

use InvalidArgumentException;

class Description
{
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException('La descripción es obligatoria');
        }

        if (strlen($this->value) < 10) {
            throw new InvalidArgumentException('La descripción debe tener al menos 10 caracteres');
        }

        if (strlen($this->value) > 1000) {
            throw new InvalidArgumentException('La descripción no puede exceder 1000 caracteres');
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