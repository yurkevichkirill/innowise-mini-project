<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

interface UserServiceInterface
{
    public function getUsers(): array;
    public function getUser(int $id): User;
    public function createUser(string $name, int $age, float $money, bool $has_visa): void;
    public function updateUser(int $id, string $name, int $age, float $money, bool $has_visa): void;
    public function deleteUser(int $id): void;
}