<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Exception;
use PDO;
use Psr\Log\LoggerInterface;

readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private ConnectionServiceInterface $context,
        private LoggerInterface $logger
    ) {}

    public function getUsers(): array
    {
        $this->logger->debug("Fetching all users from db");
        $users = [];
        $rawUsers = $this->context->getConnection()->query("SELECT * FROM users");
        foreach ($rawUsers as $row) {
            $users[] = new User($row['id'], $row['name'], $row['age'], $row['money'], (bool)$row['has_visa']);
        }
        $this->logger->debug("Fetched {count} users from db", ['count' => count($users)]);

        return $users;
    }

    public function getUser($id): ?User
    {
        $this->logger->debug("Fetching user {id} from db", ['id' => $id]);
        $stmt = $this->context->getConnection()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        if(!$this->existUser($id)) {
            $this->logger->warning("User {id} not found in db", ['id' => $id]);
            return null;
        }
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        $this->logger->debug("User {id} fetched from db", ['id', $id]);

        return new User($row['id'], $row['name'], $row['age'], $row['money'], (bool)$row['has_visa']);
    }

    public function addUser(string $name, int $age, float $money, bool $has_visa): void
    {
        $this->logger->debug("Adding user {name} age {age} money {money} visa {has_visa} to db", [
            'name' => $name,
            'age' => $age,
            'money' => $money,
            'has_visa' => $has_visa
        ]);

        $stmt = $this->context->getConnection()->prepare("INSERT INTO users (name, age, money, has_visa) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $age, $money, $has_visa]);

        $this->logger->debug("User {id} added to db", ['id' => $this->getLastId()]);
    }

    /**
     * @throws Exception
     */
    public function updateUser(int $id, string $name, int $age, float $money, bool $has_visa): void
    {
        $this->logger->debug("Update user {id} in db to name {name} age {age} money {money} visa {has_visa}", [
           'id' => $id,
           'name' => $name,
           'age' => $age,
           'money' => $money,
           'has_visa' => $has_visa
        ]);

        if(!$this->existUser($id)){
            $this->logger->warning("User {id} not found in db", ['id' => $id]);
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

        $this->logger->debug("User {id} updated in db", ['id' => $id]);
    }

    /**
     * @throws Exception
     */
    public function deleteUser($id): void
    {
        $this->logger->debug("Deleting user {id} from db", ['id' => $id]);
        if(!$this->existUser($id)){
            $this->logger->warning("User {id} not found in db", ['id' => $id]);
            throw new Exception("Object not found");
        }
        $stmt = $this->context->getConnection()->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $this->logger->debug("Deleted user {id} from db", ['id' => $id]);
    }

    public function existUser($id): bool
    {
        $stmt = $this->context->getConnection()->prepare("SELECT 1 FROM users WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch() !== false;
    }

    public function getLastId(): string
    {
        return $this->context->getConnection()->lastInsertId();
    }
}