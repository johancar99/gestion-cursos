<?php

use App\Domain\Student\ValueObjects\LastName;

describe('LastName Value Object', function () {
    
    test('can create valid last name', function () {
        $lastName = new LastName('Pérez');
        
        expect($lastName->Value())->toBe('Pérez');
    });

    test('throws exception for empty last name', function () {
        expect(fn() => new LastName(''))->toThrow(InvalidArgumentException::class, 'Last name cannot be empty');
    });

    test('throws exception for whitespace only last name', function () {
        expect(fn() => new LastName('   '))->toThrow(InvalidArgumentException::class, 'Last name cannot be empty');
    });

    test('throws exception for too long last name', function () {
        expect(fn() => new LastName(str_repeat('a', 256)))->toThrow(InvalidArgumentException::class, 'Last name cannot exceed 255 characters');
    });

    test('throws exception for invalid characters', function () {
        expect(fn() => new LastName('Pérez123'))->toThrow(InvalidArgumentException::class, 'Last name can only contain letters and spaces');
    });

    test('normalizes last name', function () {
        $lastName = new LastName('  pérez lópez  ');
        
        expect($lastName->Value())->toBe('Pérez López');
    });

    test('equals method', function () {
        $lastName1 = new LastName('Pérez');
        $lastName2 = new LastName('Pérez');
        $lastName3 = new LastName('García');
        
        expect($lastName1->equals($lastName2))->toBeTrue();
        expect($lastName1->equals($lastName3))->toBeFalse();
    });

    test('to string method', function () {
        $lastName = new LastName('Pérez');
        
        expect((string) $lastName)->toBe('Pérez');
    });
}); 