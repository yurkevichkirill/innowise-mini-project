<?php

declare(strict_types=1);

namespace App\Services;

use App\DB;
use App\Models\User;
use App\Services\UserRepositoryInterface;
use App\TestDB;
use Exception;

readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(private ConnectionServiceInterface $context) {}

    public function getUsers(): array
    {
        $users = [];
        $rawUsers = $this->context->getConnection()->query("SELECT * FROM users");
        foreach ($rawUsers as $row) {
            $users[] = new User($row['id'], $row['name'], $row['age'], $row['money'], (bool)$row['has_visa']);
        }
        return $users;
    }

    public function getUser($id): ?User
    {
        $stmt = $this->context->getConnection()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        if(!$this->existUser($id)) {
            return null;
        }
        $row = $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];
        return new User($row['id'], $row['name'], $row['age'], $row['money'], (bool)$row['has_visa']);
    }

    public function addUser(string $name, int $age, float $money, bool $has_visa): void
    {
        $stmt = $this->context->getConnection()->prepare("INSERT INTO users (name, age, money, has_visa) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $age, $money, $has_visa]);
    }

    /**
     * @throws Exception
     */
    public function updateUser(int $id, string $name, int $age, float $money, bool $has_visa): void
    {
        if(!$this->existUser($id)){
            throw new Exception("Object not found");
        }
        $stmt = $this->context->getConnection()->prepare(
            "UPDATE users 
            SET name = :name,
                age = :age,
                money = :money,
                has_visa = :has_visa
            WHERE id = :id
            ");
        $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':age' => $age,
            ':money' => $money,
            ':has_visa' => $has_visa
        ]);
    }

    /**
     * @throws Exception
     */
    public function deleteUser($id): void
    {
        if(!$this->existUser($id)){
            throw new Exception("Object not found");
        }
        $stmt = $this->context->getConnection()->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function existUser($id): bool
    {
        $stmt = $this->context->getConnection()->prepare("SELECT 1 FROM users WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch() !== false;
    }
}