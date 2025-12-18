<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\UserRepositoryInterface;

class TestUserRepository implements UserRepositoryInterface
{

    public function getUsers(): array
    {
        // TODO: Implement getUsers() method.
    }

    public function getUser(int $id): User
    {
        // TODO: Implement getUser() method.
    }

    public function addUser(string $name, int $age, float $money, bool $has_visa): void
    {
        // TODO: Implement addUser() method.
    }

    public function updateUser(int $id, string $name, int $age, float $money, bool $has_visa): void
    {
        // TODO: Implement updateUser() method.
    }

    public function deleteUser($id): void
    {
        // TODO: Implement deleteUser() method.
    }
}