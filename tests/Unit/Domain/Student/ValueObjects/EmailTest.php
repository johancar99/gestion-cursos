<?php

use App\Domain\Student\ValueObjects\Email;

describe('Email Value Object', function () {
    
    test('can create valid email', function () {
        $email = new Email('test@example.com');
        
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



    test('normalizes email to lowercase', function () {
        $email = new Email('TEST@EXAMPLE.COM');
        
        expect($email->value())->toBe('test@example.com');
    });

    test('equals method', function () {
        $email1 = new Email('test@example.com');
        $email2 = new Email('test@example.com');
        $email3 = new Email('other@example.com');
        
        expect($email1->equals($email2))->toBeTrue();
        expect($email1->equals($email3))->toBeFalse();
    });

    test('to string method', function () {
        $email = new Email('test@example.com');
        
        expect((string) $email)->toBe('test@example.com');
    });
}); 