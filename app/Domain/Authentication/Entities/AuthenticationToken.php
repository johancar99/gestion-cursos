<?php

namespace App\Domain\Authentication\Entities;

use App\Domain\Authentication\ValueObjects\TokenValue;
use App\Domain\User\ValueObjects\UserId;

class AuthenticationToken
{
    private ?int $id;
    private TokenValue $token;
    private UserId $userId;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $expiresAt;

    public function __construct(
        ?int $id,
        TokenValue $token,
        UserId $userId,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $expiresAt
    ) {
        $this->id = $id;
        $this->token = $token;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
        $this->expiresAt = $expiresAt;
    }

    public static function create(UserId $userId, int $expirationHours = 24): self
    {
        $now = new \DateTimeImmutable();
        $tokenValue = TokenValue::generate();
        
        return new self(
            null, // ID será asignado por la base de datos
            $tokenValue,
            $userId,
            $now,
            $now->modify("+{$expirationHours} hours")
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): TokenValue
    {
        return $this->token;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        return (new \DateTimeImmutable()) > $this->expiresAt;
    }
} 