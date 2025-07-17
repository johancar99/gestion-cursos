<?php

use App\Domain\Course\ValueObjects\Description;

describe('Description Value Object', function () {
    
    test('can create valid description', function () {
        $description = new Description('Aprende Laravel desde cero hasta avanzado con proyectos prácticos');
        
        expect($description->value())->toBe('Aprende Laravel desde cero hasta avanzado con proyectos prácticos');
    });

    test('cannot create empty description', function () {
        expect(fn() => new Description(''))->toThrow(InvalidArgumentException::class, 'La descripción es obligatoria');
    });

    test('cannot create description too short', function () {
        expect(fn() => new Description('Corto'))->toThrow(InvalidArgumentException::class, 'La descripción debe tener al menos 10 caracteres');
    });

    test('cannot create description too long', function () {
        $longDescription = str_repeat('a', 1001);
        
        expect(fn() => new Description($longDescription))->toThrow(InvalidArgumentException::class, 'La descripción no puede exceder 1000 caracteres');
    });

    test('can convert to string', function () {
        $description = new Description('Aprende Laravel desde cero hasta avanzado con proyectos prácticos');
        
        expect((string) $description)->toBe('Aprende Laravel desde cero hasta avanzado con proyectos prácticos');
    });
}); 