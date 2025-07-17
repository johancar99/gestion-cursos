<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\UserResponseDTO;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Services\PasswordHasher;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\UserId;
use InvalidArgumentException;

class CreateUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasher $passwordHasher
    ) {
    }

    public function execute(CreateUserDTO $dto): UserResponseDTO
    {
        // Validar que el email no exista
        $email = new Email($dto->email);
        if ($this->userRepository->existsByEmail($email)) {
            throw new InvalidArgumentException('Email already exists');
        }

        // Crear value objects
        $name = new Name($dto->name);
        $password = new Password($dto->password);

        // Crear la entidad User
        $user = User::create($name, $email, $password);

        // Guardar en el repositorio
        $this->userRepository->save($user, $dto->role);

        // Buscar el usuario guardado para obtener el ID generado
        $savedUser = $this->userRepository->findByEmail($email);
        
        if (!$savedUser) {
            throw new InvalidArgumentException('Failed to save user');
        }

        // Retornar DTO de respuesta
        return new UserResponseDTO(
            $savedUser->getId()->value(),
            $savedUser->getName()->value(),
            $savedUser->getEmail()->value(),
            $savedUser->getCreatedAt()->format('Y-m-d H:i:s'),
            $savedUser->getUpdatedAt()->format('Y-m-d H:i:s')
        );
    }
} 