<?php

namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\UserId;
use InvalidArgumentException;

class DeleteUserUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(string $userId): void
    {
        $userIdVO = new UserId($userId);
        $user = $this->userRepository->findById($userIdVO);

        if (!$user) {
            throw new InvalidArgumentException('User not found');
        }

        $this->userRepository->delete($userIdVO);
    }
} 