<?php

namespace App\Domain\User\ValueObjects;

use InvalidArgumentException;

class Password
{
    private string $value;
    private bool $isHashed;

    public function __construct(string $value, bool $isHashed = false)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Password cannot be empty');
        }
        
        if (!$isHashed && strlen($value) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters long');
        }
        
        $this->value = $value;
        $this->isHashed = $isHashed;
    }

    public static function fromHash(string $hash): self
    {
        return new self($hash, true);
    }

    public function isHashed(): bool
    {
        return $this->isHashed;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function getHashedValue(): string
    {
        if ($this->isHashed) {
            return $this->value;
        }
        return password_hash($this->value, PASSWORD_DEFAULT);
    }

    public function verify(string $plainPassword): bool
    {
        if ($this->isHashed) {
            return password_verify($plainPassword, $this->value);
        }
        return $plainPassword === $this->value;
    }

    public function equals(Password $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 