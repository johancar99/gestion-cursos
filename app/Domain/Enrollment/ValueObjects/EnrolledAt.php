<?php

namespace App\Domain\Enrollment\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

class EnrolledAt
{
    private DateTimeImmutable $value;

    public function __construct(string $value)
    {
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);
        
        if ($dateTime === false) {
            throw new InvalidArgumentException('Formato de fecha inválido. Use Y-m-d H:i:s');
        }
        
        // Verificar que la fecha es válida (no fechas imposibles como 2024-13-45)
        $errors = DateTimeImmutable::getLastErrors();
        if ($errors && $errors['error_count'] > 0) {
            throw new InvalidArgumentException('Formato de fecha inválido. Use Y-m-d H:i:s');
        }
        
        // Verificación adicional: comparar la fecha original con la fecha formateada
        // Si no coinciden, significa que la fecha era inválida
        if ($dateTime->format('Y-m-d H:i:s') !== $value) {
            throw new InvalidArgumentException('Formato de fecha inválido. Use Y-m-d H:i:s');
        }

        $this->value = $dateTime;
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }

    public static function now(): self
    {
        return new self((new DateTimeImmutable())->format('Y-m-d H:i:s'));
    }
} 