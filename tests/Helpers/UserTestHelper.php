<?php

namespace Tests\Helpers;

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\UserId;

class UserTestHelper
{
    public static function createUser(
        string $name = 'Juan Pérez',
        string $email = 'juan@example.com',
        string $password = 'password123'
    ): User {
        return User::create(
            new Name($name),
            new Email($email),
            new Password($password)
        );
    }

    public static function createUserWithId(
        string $id,
        string $name = 'Juan Pérez',
        string $email = 'juan@example.com',
        string $password = 'password123'
    ): User {
        return new User(
            new UserId($id),
            new Name($name),
            new Email($email),
            new Password($password),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );
    }

    public static function getUserData(
        string $name = 'Juan Pérez',
        string $email = 'juan@example.com',
        string $password = 'password123'
    ): array {
        return [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];
    }
} 