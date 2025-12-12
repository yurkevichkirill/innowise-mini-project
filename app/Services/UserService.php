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
}