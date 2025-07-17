<?php

namespace App\Domain\Authentication\Repositories;

use App\Domain\Authentication\Entities\AuthenticationToken;
use App\Domain\Authentication\ValueObjects\TokenValue;
use App\Domain\User\ValueObjects\UserId;

interface AuthenticationTokenRepository
{
    public function save(AuthenticationToken $token): void;
    
    public function findByToken(TokenValue $token): ?AuthenticationToken;
    
    public function findByUserId(UserId $userId): array;
    
    public function deleteByToken(TokenValue $token): void;
    
    public function deleteByUserId(UserId $userId): void;
} 