<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UpdateUserDTO;
use App\Application\User\DTOs\UserResponseDTO;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\UserId;
use InvalidArgumentException;

class UpdateUserUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(UpdateUserDTO $dto): UserResponseDTO
    {
        $userId = new UserId($dto->id);
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new InvalidArgumentException('User not found');
        }

        // Actualizar nombre si se proporciona
        if ($dto->name !== null) {
            $name = new Name($dto->name);
            $user->updateName($name);
        }

        // Actualizar email si se proporciona
        if ($dto->email !== null) {
            $email = new Email($dto->email);
            
            // Verificar que el email no exista en otro usuario
            $existingUser = $this->userRepository->findByEmail($email);
            if ($existingUser && !$existingUser->getId()->equals($user->getId())) {
                throw new InvalidArgumentException('Email already exists');
            }
            
            $user->updateEmail($email);
        }

        // Actualizar contraseña si se proporciona
        if ($dto->password !== null) {
            $password = new Password($dto->password);
            $user->updatePassword($password);
        }

        // Guardar cambios
        $this->userRepository->save($user, $dto->role);

        return new UserResponseDTO(
            $user->getId()->value(),
            $user->getName()->value(),
            $user->getEmail()->value(),
            $user->getCreatedAt()->format('Y-m-d H:i:s'),
            $user->getUpdatedAt()->format('Y-m-d H:i:s')
        );
    }
} 