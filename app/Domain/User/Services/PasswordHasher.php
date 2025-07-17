<?php

namespace App\Domain\User\Services;

interface PasswordHasher
{
    public function hash(string $password): string;
    
    public function verify(string $password, string $hash): bool;
} 