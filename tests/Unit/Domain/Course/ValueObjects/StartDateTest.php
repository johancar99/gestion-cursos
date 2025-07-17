<?php

use App\Domain\Course\ValueObjects\StartDate;

describe('StartDate Value Object', function () {
    
    test('can create valid start date', function () {
        $startDate = new StartDate(date('Y-m-d', strtotime('+1 month')));
        
        expect($startDate->value())->toBe(date('Y-m-d', strtotime('+1 month')));
    });

    test('cannot create empty start date', function () {
        expect(fn() => new StartDate(''))->toThrow(InvalidArgumentException::class, 'La fecha de inicio es obligatoria');
    });

    test('cannot create invalid date format', function () {
        expect(fn() => new StartDate('invalid-date'))->toThrow(InvalidArgumentException::class, 'El formato de fecha de inicio no es válido');
    });

    test('cannot create past date', function () {
        expect(fn() => new StartDate('2020-01-01'))->toThrow(InvalidArgumentException::class, 'La fecha de inicio no puede ser anterior a hoy');
    });

    test('can create today date', function () {
        $today = date('Y-m-d');
        $startDate = new StartDate($today);
        
        expect($startDate->value())->toBe($today);
    });

    test('can convert to string', function () {
        $startDate = new StartDate(date('Y-m-d', strtotime('+1 month')));
        
        expect((string) $startDate)->toBe(date('Y-m-d', strtotime('+1 month')));
    });
}); 