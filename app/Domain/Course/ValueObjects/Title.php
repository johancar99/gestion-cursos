<?php

namespace App\Domain\Course\ValueObjects;

use InvalidArgumentException;

class Title
{
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException('El título es obligatorio');
        }

        if (strlen($this->value) < 2) {
            throw new InvalidArgumentException('El título debe tener al menos 2 caracteres');
        }

        if (strlen($this->value) > 255) {
            throw new InvalidArgumentException('El título no puede exceder 255 caracteres');
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