<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getUsers(): array;
    public function getUser(int $id): ?User;
    public function addUser(string $name, int $age, float $money, bool $has_visa): void;
    public function updateUser(int $id, string $name, int $age, float $money, bool $has_visa): void;
    public function deleteUser($id): void;
    public function existUser($id): bool;
}