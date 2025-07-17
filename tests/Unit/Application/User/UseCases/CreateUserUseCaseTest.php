<?php

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\UseCases\CreateUserUseCase;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Services\PasswordHasher;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\UserId;
use Carbon\Carbon;

describe('CreateUserUseCase', function () {
    
    beforeEach(function () {
        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->passwordHasher = Mockery::mock(PasswordHasher::class);
        $this->useCase = new CreateUserUseCase($this->userRepository, $this->passwordHasher);
    });

    afterEach(function () {
        Mockery::close();
    });

    test('creates user successfully', function () {
        $dto = new CreateUserDTO('Juan Pérez', 'juan@example.com', 'password123', 'admin');
        
        // Mock repository to return false for existsByEmail (email doesn't exist)
        $this->userRepository->shouldReceive('existsByEmail')
            ->once()
            ->with(Mockery::type(Email::class))
            ->andReturn(false);
        
        // Mock repository to save user
        $this->userRepository->shouldReceive('save')
            ->once()
            ->with(Mockery::type(User::class), 'admin');
        
        // Mock repository to find saved user
        $this->userRepository->shouldReceive('findByEmail')
            ->once()
            ->with(Mockery::type(Email::class))
            ->andReturn(Mockery::mock(User::class, [
                'getId' => Mockery::mock(UserId::class, ['value' => 'user-123']),
                'getName' => Mockery::mock(Name::class, ['value' => 'Juan Pérez']),
                'getEmail' => Mockery::mock(Email::class, ['value' => 'juan@example.com']),
                'getCreatedAt' => new \DateTimeImmutable(),
                'getUpdatedAt' => new \DateTimeImmutable(),
            ]));
        
        $result = $this->useCase->execute($dto);
        
        expect($result)->toBeInstanceOf(\App\Application\User\DTOs\UserResponseDTO::class);
        expect($result->name)->toBe('Juan Pérez');
        expect($result->email)->toBe('juan@example.com');
        expect($result->id)->not->toBeEmpty();
        expect($result->createdAt)->not->toBeEmpty();
        expect($result->updatedAt)->not->toBeEmpty();
    });

    test('throws exception when email already exists', function () {
        $dto = new CreateUserDTO('Juan Pérez', 'juan@example.com', 'password123', 'admin');
        
        // Mock repository to return true for existsByEmail (email exists)
        $this->userRepository->shouldReceive('existsByEmail')
            ->once()
            ->with(Mockery::type(Email::class))
            ->andReturn(true);
        
        expect(fn() => $this->useCase->execute($dto))
            ->toThrow(InvalidArgumentException::class, 'Email already exists');
    });

    test('validates email format through value object', function () {
        $dto = new CreateUserDTO('Juan Pérez', 'invalid-email', 'password123', 'admin');
        
        expect(fn() => $this->useCase->execute($dto))
            ->toThrow(InvalidArgumentException::class, 'Invalid email format');
    });

    test('validates name through value object', function () {
        // Arrange
        $dto = new CreateUserDTO('J', 'juan@example.com', 'password123', 'admin');
    
        // Stub innecesario pero obligatorio para evitar errores de Mockery
        $this->userRepository->shouldReceive('existsByEmail')->andReturn(false);
    
        // Assert
        expect(fn() => $this->useCase->execute($dto))
            ->toThrow(InvalidArgumentException::class, 'Name must be at least 2 characters long');
    });
    

    test('validates password through value object', function () {
        $dto = new CreateUserDTO('Juan Pérez', 'juan@example.com', '123', 'admin');

        // Stub innecesario pero obligatorio para evitar errores de Mockery
        $this->userRepository->shouldReceive('existsByEmail')->andReturn(false);
        
        expect(fn() => $this->useCase->execute($dto))
            ->toThrow(InvalidArgumentException::class, 'Password must be at least 8 characters long');
    });

    test('creates user with valid value objects', function () {
        $dto = new CreateUserDTO('Juan Pérez', 'juan@example.com', 'password123', 'admin');
        
        $this->userRepository->shouldReceive('existsByEmail')
            ->once()
            ->andReturn(false);
        
        $this->userRepository->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (User $user) {
                        return $user->getName()->value() === 'Juan Pérez' &&
               $user->getEmail()->value() === 'juan@example.com';
            }), 'admin');
        
        $this->userRepository->shouldReceive('findByEmail')
            ->once()
            ->andReturn(Mockery::mock(User::class, [
                'getId' => Mockery::mock(UserId::class, ['value' => 'user-123']),
                'getName' => Mockery::mock(Name::class, ['value' => 'Juan Pérez']),
                'getEmail' => Mockery::mock(Email::class, ['value' => 'juan@example.com']),
                'getCreatedAt' => new \DateTimeImmutable(),
                'getUpdatedAt' => new \DateTimeImmutable(),
            ]));
        
        $result = $this->useCase->execute($dto);
        
        expect($result->name)->toBe('Juan Pérez');
        expect($result->email)->toBe('juan@example.com');
    });

    test('generates unique user id', function () {
        $dto = new CreateUserDTO('Juan Pérez', 'juan@example.com', 'password123', 'admin');
        
        $this->userRepository->shouldReceive('existsByEmail')
            ->once()
            ->andReturn(false);
        
        $this->userRepository->shouldReceive('save')
            ->once()
            ->with(Mockery::type(User::class), 'admin');
        
        $this->userRepository->shouldReceive('findByEmail')
            ->once()
            ->andReturn(Mockery::mock(User::class, [
                'getId' => Mockery::mock(UserId::class, ['value' => 'user-123']),
                'getName' => Mockery::mock(Name::class, ['value' => 'Juan Pérez']),
                'getEmail' => Mockery::mock(Email::class, ['value' => 'juan@example.com']),
                'getCreatedAt' => new \DateTimeImmutable(),
                'getUpdatedAt' => new \DateTimeImmutable(),
            ]));
        
        $result1 = $this->useCase->execute($dto);
        
        // Create another user
        $dto2 = new CreateUserDTO('María García', 'maria@example.com', 'password123', 'secretary');
        
        $this->userRepository->shouldReceive('existsByEmail')
            ->once()
            ->andReturn(false);
        
        $this->userRepository->shouldReceive('save')
            ->once()
            ->with(Mockery::type(User::class), 'secretary');
        
        $this->userRepository->shouldReceive('findByEmail')
            ->once()
            ->andReturn(Mockery::mock(User::class, [
                'getId' => Mockery::mock(UserId::class, ['value' => 'user-456']),
                'getName' => Mockery::mock(Name::class, ['value' => 'María García']),
                'getEmail' => Mockery::mock(Email::class, ['value' => 'maria@example.com']),
                'getCreatedAt' => new \DateTimeImmutable(),
                'getUpdatedAt' => new \DateTimeImmutable(),
            ]));
        
        $result2 = $this->useCase->execute($dto2);
        
        expect($result1->id)->not->toBe($result2->id);
    });
}); 