<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepository as UserRepositoryInterface;
use App\Domain\User\Services\PasswordHasher;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\UserId;
use App\Models\User as UserModel;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private PasswordHasher $passwordHasher
    ) {
    }

    public function save(User $user, ?string $role = null): void
    {
        $userData = [
            'name' => $user->getName()->value(),
            'email' => $user->getEmail()->value(),
            'password' => $user->getPassword()->isHashed() ? $user->getPassword()->value() : $user->getPassword()->value(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt(),
        ];

        if ($user->getId()->value() !== '') {
            UserModel::where('id', $user->getId()->value())->update($userData);
            $userModel = UserModel::find($user->getId()->value());
        } else {
            $userModel = UserModel::create($userData);
            $reflection = new \ReflectionClass($user);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($user, new UserId($userModel->id));
        }

        // Asignar o sincronizar el rol si se proporciona
        if ($role) {
            $userModel->syncRoles([$role]);
        }
    }

    public function findById(UserId $id): ?User
    {
        // Si el ID está vacío, no buscar
        if ($id->value() === '') {
            return null;
        }
        
        $userModel = UserModel::find($id->value());

        if (!$userModel) {
            return null;
        }

        return $this->mapToDomain($userModel);
    }

    public function findByEmail(Email $email): ?User
    {
        $userModel = UserModel::where('email', $email->value())->first();

        if (!$userModel) {
            return null;
        }

        return $this->mapToDomain($userModel);
    }

    public function findAll(): array
    {
        $userModels = UserModel::all();

        return array_map(function (UserModel $userModel) {
            return $this->mapToDomain($userModel);
        }, $userModels->all());
    }

    public function findByFilters(array $filters): array
    {
        $query = UserModel::query();

        if (isset($filters['name'])) {
            $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
        }

        if (isset($filters['email'])) {
            $query->where('email', 'LIKE', '%' . $filters['email'] . '%');
        }

        if (isset($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        if (isset($filters['date_range'])) {
            $query->whereBetween('created_at', [$filters['date_range']['start'], $filters['date_range']['end']]);
        }

        $userModels = $query->get();

        return array_map(function (UserModel $userModel) {
            return $this->mapToDomain($userModel);
        }, $userModels->all());
    }

    public function delete(UserId $id): void
    {
        if ($id->value() !== '') {
            UserModel::where('id', $id->value())->delete();
        }
    }

    public function existsByEmail(Email $email): bool
    {
        return UserModel::where('email', $email->value())->exists();
    }

    private function mapToDomain(UserModel $userModel): User
    {
        // Crear un Password desde el hash almacenado
        $password = Password::fromHash($userModel->password);
        
        return new User(
            new UserId((string) $userModel->id),
            new Name($userModel->name),
            new Email($userModel->email),
            $password,
            $userModel->created_at ? $userModel->created_at->toDateTimeImmutable() : new \DateTimeImmutable(),
            $userModel->updated_at ? $userModel->updated_at->toDateTimeImmutable() : new \DateTimeImmutable()
        );
    }
}
