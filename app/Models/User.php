<?php

declare(strict_types=1);

namespace App\Models;

use App\Container;
use App\Router;

class User
{
    public function __construct(
        private int $id,
        private string $name,
        private int $age,
        private float $money,
        private bool $has_visa
    ) {}

    public function getAge(): int
    {
        return $this->age;
    }

    public function isHasVisa(): bool
    {
        return $this->has_visa;
    }

    public function getMoney(): float
    {
        return $this->money;
    }

    public function getName(): string
    {
        return $this->name;
    }


}