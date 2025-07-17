<?php

use App\Domain\User\ValueObjects\Name;

describe('Name Value Object', function () {
    
    test('can create valid name', function () {
        $name = new Name('Juan Pérez');
        
        expect($name->value())->toBe('Juan Pérez');
    });

    test('trims whitespace', function () {
        $name = new Name('  Juan Pérez  ');
        
        expect($name->value())->toBe('Juan Pérez');
    });

    test('throws exception for empty name', function () {
        expect(fn() => new Name(''))->toThrow(InvalidArgumentException::class, 'Name cannot be empty');
    });

    test('throws exception for whitespace only name', function () {
        expect(fn() => new Name('   '))->toThrow(InvalidArgumentException::class, 'Name cannot be empty');
    });

    test('throws exception for name shorter than 2 characters', function () {
        expect(fn() => new Name('J'))->toThrow(InvalidArgumentException::class, 'Name must be at least 2 characters long');
    });

    test('throws exception for name longer than 255 characters', function () {
        $longName = str_repeat('a', 256);
        expect(fn() => new Name($longName))->toThrow(InvalidArgumentException::class, 'Name cannot exceed 255 characters');
    });

    test('accepts name with exactly 2 characters', function () {
        $name = new Name('Jo');
        
        expect($name->value())->toBe('Jo');
    });

    test('accepts name with exactly 255 characters', function () {
        $longName = str_repeat('a', 255);
        $name = new Name($longName);
        
        expect($name->value())->toBe($longName);
    });

    test('can be converted to string', function () {
        $name = new Name('Juan Pérez');
        
        expect((string) $name)->toBe('Juan Pérez');
    });

    test('equals method works correctly', function () {
        $name1 = new Name('Juan Pérez');
        $name2 = new Name('Juan Pérez');
        $name3 = new Name('María García');
        
        expect($name1->equals($name2))->toBeTrue();
        expect($name1->equals($name3))->toBeFalse();
    });

    test('equals is case sensitive', function () {
        $name1 = new Name('Juan Pérez');
        $name2 = new Name('juan pérez');
        
        expect($name1->equals($name2))->toBeFalse();
    });

    test('handles special characters', function () {
        $name = new Name('José María O\'Connor');
        
        expect($name->value())->toBe('José María O\'Connor');
    });

    test('handles numbers in name', function () {
        $name = new Name('Juan123');
        
        expect($name->value())->toBe('Juan123');
    });
}); 