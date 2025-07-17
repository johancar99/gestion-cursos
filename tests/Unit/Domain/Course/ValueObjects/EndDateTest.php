<?php

use App\Domain\Course\ValueObjects\EndDate;
use App\Domain\Course\ValueObjects\StartDate;

describe('EndDate Value Object', function () {
    
    test('can create valid end date', function () {
        $startDate = new StartDate(date('Y-m-d', strtotime('+1 month')));
        $endDate = new EndDate(date('Y-m-d', strtotime('+3 months')), $startDate);
        
        expect($endDate->value())->toBe(date('Y-m-d', strtotime('+3 months')));
    });

    test('cannot create empty end date', function () {
        expect(fn() => new EndDate(''))->toThrow(InvalidArgumentException::class, 'La fecha de fin es obligatoria');
    });

    test('cannot create invalid date format', function () {
        expect(fn() => new EndDate('invalid-date'))->toThrow(InvalidArgumentException::class, 'El formato de fecha de fin no es válido');
    });

    test('cannot create past date', function () {
        expect(fn() => new EndDate('2020-01-01'))->toThrow(InvalidArgumentException::class, 'La fecha de fin no puede ser anterior a hoy');
    });

    test('cannot create end date before start date', function () {
        $startDate = new StartDate(date('Y-m-d', strtotime('+3 months')));
        
        expect(fn() => new EndDate(date('Y-m-d', strtotime('+1 month')), $startDate))->toThrow(InvalidArgumentException::class, 'La fecha de fin debe ser posterior a la fecha de inicio');
    });

    test('cannot create end date equal to start date', function () {
        $startDate = new StartDate(date('Y-m-d', strtotime('+1 month')));
        
        expect(fn() => new EndDate(date('Y-m-d', strtotime('+1 month')), $startDate))->toThrow(InvalidArgumentException::class, 'La fecha de fin debe ser posterior a la fecha de inicio');
    });

    test('can create end date without start date', function () {
        $endDate = new EndDate(date('Y-m-d', strtotime('+3 months')));
        
        expect($endDate->value())->toBe(date('Y-m-d', strtotime('+3 months')));
    });

    test('can convert to string', function () {
        $startDate = new StartDate(date('Y-m-d', strtotime('+1 month')));
        $endDate = new EndDate(date('Y-m-d', strtotime('+3 months')), $startDate);
        
        expect((string) $endDate)->toBe(date('Y-m-d', strtotime('+3 months')));
    });
}); 