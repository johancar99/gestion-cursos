<?php

use App\Domain\Student\ValueObjects\FirstName;

describe('FirstName Value Object', function () {
    
    test('can create valid first name', function () {
        $firstName = new FirstName('Juan');
        
        expect($firstName->Value())->toBe('Juan');
    });

    test('throws exception for empty first name', function () {
        expect(fn() => new FirstName(''))->toThrow(InvalidArgumentException::class, 'First name cannot be empty');
    });

    test('throws exception for whitespace only first name', function () {
        expect(fn() => new FirstName('   '))->toThrow(InvalidArgumentException::class, 'First name cannot be empty');
    });

    test('throws exception for too long first name', function () {
        expect(fn() => new FirstName(str_repeat('a', 256)))->toThrow(InvalidArgumentException::class, 'First name cannot exceed 255 characters');
    });

    test('throws exception for invalid characters', function () {
        expect(fn() => new FirstName('Juan123'))->toThrow(InvalidArgumentException::class, 'First name can only contain letters and spaces');
    });

    test('normalizes first name', function () {
        $firstName = new FirstName('  juan carlos  ');
        
        expect($firstName->Value())->toBe('Juan Carlos');
    });

    test('equals method', function () {
        $firstName1 = new FirstName('Juan');
        $firstName2 = new FirstName('Juan');
        $firstName3 = new FirstName('María');
        
        expect($firstName1->equals($firstName2))->toBeTrue();
        expect($firstName1->equals($firstName3))->toBeFalse();
    });

    test('to string method', function () {
        $firstName = new FirstName('Juan');
        
        expect((string) $firstName)->toBe('Juan');
    });
}); 