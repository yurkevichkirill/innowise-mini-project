<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function getUsers()
    {
        return $this->repository->getUsers();
    }
}