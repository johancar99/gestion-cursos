<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\UserId;

interface UserRepository
{
    public function save(User $user): void;
    
    public function findById(UserId $id): ?User;
    
    public function findByEmail(Email $email): ?User;
    
    public function findAll(): array;
    
    public function findByFilters(array $filters): array;
    
    public function delete(UserId $id): void;
    
    public function existsByEmail(Email $email): bool;
} 