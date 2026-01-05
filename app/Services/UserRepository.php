<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserDTO;
use Exception;
use PDO;
use Psr\Log\LoggerInterface;

readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private ConnectionServiceInterface $context,
        private LoggerInterface $logger
    ) {}

    public function getAll(): array
    {
        $users = [];
        $rawUsers = $this->context->getConnection()->query("SELECT * FROM users ORDER BY id");
        foreach ($rawUsers as $row) {
            $users[] = new UserDTO($row['id'], $row['name'], $row['age'], $row['money'], (bool)$row['has_visa']);
        }

        return $users;
    }

    public function get($id): ?UserDTO
    {
        $stmt = $this->context->getConnection()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        if(!$this->existUser($id)) {
            $this->logger->warning("User {id} not found in db", ['id' => $id]);
            return null;
        }
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

        return new UserDTO($row['id'], $row['name'], $row['age'], $row['money'], (bool)$row['has_visa']);
    }

    public function save(UserDTO $dto): UserDTO
    {
        $data = $dto->toArray();

        $updates = [];
        $params = [':id' => $data['id']];
        if(isset($data['name'])) {
            $updates[] = 'name = :name';
            $params[':name'] = $data['name'];
        }
        if(isset($data['age'])) {
            $updates[] = 'age = :age';
            $params[':age'] = $data['age'];
        }
        if(isset($data['money'])) {
            $updates[] = 'money = :money';
            $params[':money'] = (float)$data['money'];
        }
        if(isset($data['has_visa'])) {
            $updates[] = 'has_visa = :has_visa';
            $params[':has_visa'] = $data['has_visa'] ? 1 : 0;
        }

        if($this->existUser($dto->getId())) {
            $sql = "UPDATE users SET " . implode(',', $updates) . " WHERE id = :id";
            $stmt = $this->context->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $this->get($params[':id']);
        } else {
            $stmt = $this->context->getConnection()->prepare("INSERT INTO users (name, age, money, has_visa) VALUES (?, ?, ?, ?)");
            $stmt->execute([$params[':name'], $params[':age'], $params[':money'], $params[':has_visa']]);
            return $this->get($this->getLastId());
        }
    }

    /**
     * @throws Exception
     */
    public function delete($id): void
    {
        if(!$this->existUser($id)){
            $this->logger->warning("User {id} not found in db", ['id' => $id]);
            throw new Exception("User Not Found");
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

    public function getLastId(): string
    {
        return $this->context->getConnection()->lastInsertId();
    }
}