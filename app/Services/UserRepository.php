<?php

declare(strict_types=1);

namespace App\Services;

use App\DB;
use App\Models\User;
use App\Services\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private DB $context) {}

    public function getUsers(): array
    {
        $users = [];
        $rawUsers = $this->context->getConnection()->query("SELECT * FROM users");
        foreach($rawUsers as $row) {
            $users[] = new User($row['id'], $row['name'], $row['age'], $row['money'], $row['has_visa']);
        }
        return $users;
    }
}