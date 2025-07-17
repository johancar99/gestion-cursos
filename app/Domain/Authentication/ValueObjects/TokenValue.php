<?php

namespace App\Domain\Authentication\ValueObjects;

use InvalidArgumentException;

class TokenValue
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Token value cannot be empty');
        }
        
        $this->value = $value;
    }

    public static function generate(): self
    {
        return new self(bin2hex(random_bytes(32)));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(TokenValue $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 