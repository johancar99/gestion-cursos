<?php

namespace App\Application\Authentication\UseCases;

use App\Application\Authentication\DTOs\LoginDTO;
use App\Application\Authentication\DTOs\LoginResponseDTO;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Services\PasswordHasher;
use App\Domain\User\ValueObjects\Email;
use App\Models\User;
use InvalidArgumentException;

class LoginUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasher $passwordHasher
    ) {
    }

    public function execute(LoginDTO $dto): LoginResponseDTO
    {
        // Buscar usuario por email
        $email = new Email($dto->email);
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new InvalidArgumentException('Invalid credentials');
        }

        // Verificar contraseña
        if (!$this->passwordHasher->verify($dto->password, $user->getPassword()->value())) {
            throw new InvalidArgumentException('Invalid credentials');
        }

        // Obtener el modelo Eloquent para usar Sanctum
        $eloquentUser = User::find($user->getId()->value());
        
        if (!$eloquentUser) {
            throw new InvalidArgumentException('Invalid credentials');
        }

        // Crear token de Sanctum
        $token = $eloquentUser->createToken('auth-token');

        return new LoginResponseDTO(
            $token->plainTextToken,
            'Bearer',
            60 * 60 * 24, // 24 horas en segundos
            $user->getId()->value(),
            $user->getName()->value(),
            $user->getEmail()->value()
        );
    }
} 