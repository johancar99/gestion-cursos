<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UserResponseDTO;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepository;

class GetAllUsersUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(array $filters = []): array
    {
        if (empty($filters)) {
            $users = $this->userRepository->findAll();
        } else {
            $users = $this->userRepository->findByFilters($filters);
        }

        return array_map(function (User $user) {
            return new UserResponseDTO(
                $user->getId()->value(),
                $user->getName()->value(),
                $user->getEmail()->value(),
                $user->getCreatedAt()->format('Y-m-d H:i:s'),
                $user->getUpdatedAt()->format('Y-m-d H:i:s')
            );
        }, $users);
    }
} 