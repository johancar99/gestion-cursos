<?php

use App\Domain\Enrollment\ValueObjects\EnrolledAt;

describe('EnrolledAt', function () {
    
    test('can create enrolled at with valid date', function () {
        $date = '2024-01-15 10:30:00';
        $enrolledAt = new EnrolledAt($date);
        
        expect($enrolledAt->value()->format('Y-m-d H:i:s'))->toBe($date);
    });

    test('can get string representation', function () {
        $date = '2024-01-15 10:30:00';
        $enrolledAt = new EnrolledAt($date);
        
        expect($enrolledAt->toString())->toBe($date);
    });

    test('can create now timestamp', function () {
        $enrolledAt = EnrolledAt::now();
        
        expect($enrolledAt->value())->toBeInstanceOf(DateTimeImmutable::class);
        
        // Verificar que la fecha es reciente (dentro de los últimos 5 segundos)
        $now = new DateTimeImmutable();
        $diff = abs($now->getTimestamp() - $enrolledAt->value()->getTimestamp());
        expect($diff)->toBeLessThanOrEqual(5);
    });

    test('throws exception for invalid date format', function () {
        expect(fn() => new EnrolledAt('invalid-date'))
            ->toThrow(InvalidArgumentException::class, 'Formato de fecha inválido. Use Y-m-d H:i:s');
    });

    test('throws exception for invalid date', function () {
        expect(fn() => new EnrolledAt('2024-13-45 25:70:99'))
            ->toThrow(InvalidArgumentException::class, 'Formato de fecha inválido. Use Y-m-d H:i:s');
    });
}); 