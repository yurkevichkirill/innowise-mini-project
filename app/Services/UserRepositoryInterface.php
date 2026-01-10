<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserDTO;

interface UserRepositoryInterface
{
    public function getAll(): array;

    public function get(int $id): UserDTO;

    public function delete(int $id): void;

    public function save(UserDTO $dto): UserDTO;

    public function existUser($id): bool;

    public function getLastId(): string;
}
