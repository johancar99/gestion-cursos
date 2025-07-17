<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UserResponseDTO;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\UserId;
use InvalidArgumentException;

class GetUserUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(string $userId): UserResponseDTO
    {
        $userIdVO = new UserId($userId);
        $user = $this->userRepository->findById($userIdVO);

        if (!$user) {
            throw new InvalidArgumentException('User not found');
        }

        return new UserResponseDTO(
            $user->getId()->value(),
            $user->getName()->value(),
            $user->getEmail()->value(),
            $user->getCreatedAt()->format('Y-m-d H:i:s'),
            $user->getUpdatedAt()->format('Y-m-d H:i:s')
        );
    }
} 