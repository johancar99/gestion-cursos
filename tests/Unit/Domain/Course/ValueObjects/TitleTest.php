<?php

use App\Domain\Course\ValueObjects\Title;

describe('Title Value Object', function () {
    
    test('can create valid title', function () {
        $title = new Title('Curso de Laravel');
        
        expect($title->value())->toBe('Curso de Laravel');
    });

    test('cannot create empty title', function () {
        expect(fn() => new Title(''))->toThrow(InvalidArgumentException::class, 'El título es obligatorio');
    });

    test('cannot create title too short', function () {
        expect(fn() => new Title('A'))->toThrow(InvalidArgumentException::class, 'El título debe tener al menos 2 caracteres');
    });

    test('cannot create title too long', function () {
        $longTitle = str_repeat('a', 256);
        
        expect(fn() => new Title($longTitle))->toThrow(InvalidArgumentException::class, 'El título no puede exceder 255 caracteres');
    });

    test('can convert to string', function () {
        $title = new Title('Curso de Laravel');
        
        expect((string) $title)->toBe('Curso de Laravel');
    });
}); 