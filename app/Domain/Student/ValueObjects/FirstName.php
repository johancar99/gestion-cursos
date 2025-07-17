<?php

namespace App\Domain\Student\ValueObjects;

use InvalidArgumentException;

class FirstName
{
    private string $value;

    public function __construct(string $value)
    {
        $trimmedValue = trim($value);
        
        if (empty($trimmedValue)) {
            throw new InvalidArgumentException('First name cannot be empty');
        }
        
        if (strlen($trimmedValue) > 255) {
            throw new InvalidArgumentException('First name cannot exceed 255 characters');
        }
        
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $trimmedValue)) {
            throw new InvalidArgumentException('First name can only contain letters and spaces');
        }
        
        $this->value = ucwords(strtolower($trimmedValue));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(FirstName $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 