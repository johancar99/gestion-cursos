<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Authentication\Entities\AuthenticationToken;
use App\Domain\Authentication\Repositories\AuthenticationTokenRepository as AuthenticationTokenRepositoryInterface;
use App\Domain\Authentication\ValueObjects\TokenValue;
use App\Domain\User\ValueObjects\UserId;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticationTokenRepository implements AuthenticationTokenRepositoryInterface
{
    public function save(AuthenticationToken $token): void
    {
        $tokenData = [
            'token' => $token->getToken()->value(),
            'tokenable_id' => $token->getUserId()->value(),
            'tokenable_type' => 'App\Models\User',
            'name' => 'auth-token',
            'created_at' => $token->getCreatedAt(),
            'expires_at' => $token->getExpiresAt(),
        ];

        if ($token->getId() !== null) {
            // Actualizar token existente
            PersonalAccessToken::where('id', $token->getId())->update($tokenData);
        } else {
            // Crear nuevo token
            $tokenModel = PersonalAccessToken::create($tokenData);
            
            // Asignar el ID generado usando reflection
            $reflection = new \ReflectionClass($token);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($token, $tokenModel->id);
        }
    }

    public function findByToken(TokenValue $token): ?AuthenticationToken
    {
        $tokenModel = PersonalAccessToken::where('token', $token->value())->first();

        if (!$tokenModel) {
            return null;
        }

        return $this->mapToDomain($tokenModel);
    }

    public function findByUserId(UserId $userId): array
    {
        $tokens = PersonalAccessToken::where('tokenable_id', $userId->value())
            ->where('tokenable_type', 'App\Models\User')
            ->get();

        return $tokens->map(fn($token) => $this->mapToDomain($token))->toArray();
    }

    public function deleteByToken(TokenValue $token): void
    {
        PersonalAccessToken::where('token', $token->value())->delete();
    }

    public function deleteByUserId(UserId $userId): void
    {
        PersonalAccessToken::where('tokenable_id', $userId->value())
            ->where('tokenable_type', 'App\Models\User')
            ->delete();
    }

    private function mapToDomain(PersonalAccessToken $tokenModel): AuthenticationToken
    {
        return new AuthenticationToken(
            $tokenModel->id,
            new TokenValue($tokenModel->token),
            new UserId($tokenModel->tokenable_id),
            $tokenModel->created_at,
            $tokenModel->expires_at
        );
    }
} 