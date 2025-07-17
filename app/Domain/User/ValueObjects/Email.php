<?php

namespace App\Domain\User\ValueObjects;

use InvalidArgumentException;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $trimmedValue = trim($value);
        
        if (empty($trimmedValue)) {
            throw new InvalidArgumentException('Email cannot be empty');
        }
        
        if (!filter_var($trimmedValue, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
        
        if (strlen($trimmedValue) > 255) {
            throw new InvalidArgumentException('Email cannot exceed 255 characters');
        }
        
        $this->value = strtolower($trimmedValue);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 