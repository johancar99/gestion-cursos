<?php

namespace App\Domain\Enrollment\ValueObjects;

class EnrollmentId
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(EnrollmentId $other): bool
    {
        return $this->value === $other->value;
    }
} 