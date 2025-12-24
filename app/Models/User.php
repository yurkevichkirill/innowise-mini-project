<?php

declare(strict_types=1);

namespace App\Models;

readonly class User
{
    public function __construct(
        private int    $id,
        private string $name,
        private int    $age,
        private float  $money,
        private bool   $has_visa
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getMoney(): float
    {
        return $this->money;
    }

    public function isHasVisa(): bool
    {
        return $this->has_visa;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'money' => $this->money,
            'has_visa' => $this->has_visa
        ];
    }

}