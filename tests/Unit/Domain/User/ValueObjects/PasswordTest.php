<?php

use App\Domain\User\ValueObjects\Password;

describe('Password Value Object', function () {
    
    test('can create valid password', function () {
        $password = new Password('password123');
        
        expect($password->value())->toBe('password123');
    });

    test('throws exception for empty password', function () {
        expect(fn() => new Password(''))->toThrow(InvalidArgumentException::class, 'Password cannot be empty');
    });

    test('throws exception for password shorter than 8 characters', function () {
        expect(fn() => new Password('123'))->toThrow(InvalidArgumentException::class, 'Password must be at least 8 characters long');
    });

    test('accepts password with exactly 8 characters', function () {
        $password = new Password('12345678');
        
        expect($password->value())->toBe('12345678');
    });

    test('accepts password longer than 8 characters', function () {
        $password = new Password('verylongpassword123');
        
        expect($password->value())->toBe('verylongpassword123');
    });

    test('can be converted to string', function () {
        $password = new Password('password123');
        
        expect((string) $password)->toBe('password123');
    });

    test('equals method works correctly', function () {
        $password1 = new Password('password123');
        $password2 = new Password('password123');
        $password3 = new Password('different123');
        
        expect($password1->equals($password2))->toBeTrue();
        expect($password1->equals($password3))->toBeFalse();
    });

    test('getHashedValue returns hashed password', function () {
        $password = new Password('password123');
        $hashedValue = $password->getHashedValue();
        
        expect($hashedValue)->not->toBe('password123');
        expect(password_verify('password123', $hashedValue))->toBeTrue();
    });

    test('verify method works correctly', function () {
        $password = new Password('password123');
        $hashedValue = $password->getHashedValue();
        
        expect(password_verify('password123', $hashedValue))->toBeTrue();
        expect(password_verify('wrongpassword', $hashedValue))->toBeFalse();
    });

    test('handles special characters in password', function () {
        $password = new Password('p@ssw0rd!123');
        
        expect($password->value())->toBe('p@ssw0rd!123');
    });

    test('handles spaces in password', function () {
        $password = new Password('password with spaces');
        
        expect($password->value())->toBe('password with spaces');
    });

    test('handles unicode characters in password', function () {
        $password = new Password('pässwörd123');
        
        expect($password->value())->toBe('pässwörd123');
    });
}); 