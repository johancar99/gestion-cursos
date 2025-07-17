<?php

use App\Domain\Enrollment\ValueObjects\EnrollmentId;

describe('EnrollmentId', function () {
    
    test('can create enrollment id', function () {
        $id = new EnrollmentId('123');
        
        expect($id->value())->toBe('123');
    });

    test('can compare enrollment ids', function () {
        $id1 = new EnrollmentId('123');
        $id2 = new EnrollmentId('123');
        $id3 = new EnrollmentId('456');
        
        expect($id1->equals($id2))->toBeTrue();
        expect($id1->equals($id3))->toBeFalse();
    });

    test('can handle empty string', function () {
        $id = new EnrollmentId('');
        
        expect($id->value())->toBe('');
    });
}); 