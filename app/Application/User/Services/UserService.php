<?php

namespace App\Application\User\Services;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\UpdateUserDTO;
use App\Application\User\DTOs\UserResponseDTO;
use App\Application\User\UseCases\CreateUserUseCase;
use App\Application\User\UseCases\DeleteUserUseCase;
use App\Application\User\UseCases\GetAllUsersUseCase;
use App\Application\User\UseCases\GetUserUseCase;
use App\Application\User\UseCases\UpdateUserUseCase;

class UserService
{
    public function __construct(
        private CreateUserUseCase $createUserUseCase,
        private GetUserUseCase $getUserUseCase,
        private GetAllUsersUseCase $getAllUsersUseCase,
        private UpdateUserUseCase $updateUserUseCase,
        private DeleteUserUseCase $deleteUserUseCase
    ) {
    }

    public function createUser(CreateUserDTO $dto): UserResponseDTO
    {
        return $this->createUserUseCase->execute($dto);
    }

    public function getUser(string $userId): UserResponseDTO
    {
        return $this->getUserUseCase->execute($userId);
    }

    public function getAllUsers(): array
    {
        return $this->getAllUsersUseCase->execute();
    }

    public function getUsersByFilters(array $filters): array
    {
        return $this->getAllUsersUseCase->execute($filters);
    }

    public function updateUser(UpdateUserDTO $dto): UserResponseDTO
    {
        return $this->updateUserUseCase->execute($dto);
    }

    public function deleteUser(string $userId): void
    {
        $this->deleteUserUseCase->execute($userId);
    }
} 