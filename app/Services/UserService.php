<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\UserServiceInterface;

readonly class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function getUsers(): array
    {
        return $this->repository->getUsers();
    }

    public function getUser($id): User
    {
        return $this->repository->getUser($id);
    }

    public function createUser(string $name, int $age, float $money, bool $has_visa): void
    {
        $this->repository->addUser($name, $age, $money, $has_visa);
    }

    public function updateUser(int $id, string $name, int $age, float $money, bool $has_visa): void
    {
        $this->repository->updateUser($id, $name, $age, $money, $has_visa);
    }

    public function deleteUser(int $id): void
    {
        $this->repository->deleteUser($id);
    }
}