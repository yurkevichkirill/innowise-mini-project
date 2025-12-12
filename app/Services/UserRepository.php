<?php

declare(strict_types=1);

namespace App\Services;

use App\DB;
use App\Models\User;
use App\Services\UserRepositoryInterface;

readonly class UserRepository implements UserRepositoryInterface
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

    public function getUser($id): User
    {
        $stmt = $this->context->getConnection()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];
        return new User($row['id'], $row['name'], $row['age'], $row['money'], $row['has_visa']);
    }
}