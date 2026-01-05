<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserDTO;

interface UserServiceInterface
{
    public function create(string $name, int $age, float $money, bool $has_visa): ?UserDTO;
    public function update(?int $id, ?string $name, ?int $age, ?float $money, ?bool $has_visa): ?UserDTO;
}