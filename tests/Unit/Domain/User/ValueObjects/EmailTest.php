<?php

use App\Domain\User\ValueObjects\Email;

describe('Email Value Object', function () {
    
    test('can create valid email', function () {
        $email = new Email('test@example.com');
        
        expect($email->value())->toBe('test@example.com');
    });

    test('converts email to lowercase', function () {
        $email = new Email('TEST@EXAMPLE.COM');
        
        expect($email->value())->toBe('test@example.com');
    });

    test('trims whitespace', function () {
        $email = new Email('  test@example.com  ');
        
        expect($email->value())->toBe('test@example.com');
    });

    test('throws exception for empty email', function () {
        expect(fn() => new Email(''))->toThrow(InvalidArgumentException::class, 'Email cannot be empty');
    });

    test('throws exception for whitespace only email', function () {
        expect(fn() => new Email('   '))->toThrow(InvalidArgumentException::class, 'Email cannot be empty');
    });

    test('throws exception for invalid email format', function () {
        expect(fn() => new Email('invalid-email'))->toThrow(InvalidArgumentException::class, 'Invalid email format');
    });

    test('throws exception for email without domain', function () {
        expect(fn() => new Email('test@'))->toThrow(InvalidArgumentException::class, 'Invalid email format');
    });

    test('throws exception for email without @ symbol', function () {
        expect(fn() => new Email('testexample.com'))->toThrow(InvalidArgumentException::class, 'Invalid email format');
    });

    

    test('can be converted to string', function () {
        $email = new Email('test@example.com');
        
        expect((string) $email)->toBe('test@example.com');
    });

    test('equals method works correctly', function () {
        $email1 = new Email('test@example.com');
        $email2 = new Email('test@example.com');
        $email3 = new Email('other@example.com');
        
        expect($email1->equals($email2))->toBeTrue();
        expect($email1->equals($email3))->toBeFalse();
    });

    test('equals is case insensitive', function () {
        $email1 = new Email('TEST@EXAMPLE.COM');
        $email2 = new Email('test@example.com');
        
        expect($email1->equals($email2))->toBeTrue();
    });
}); 