<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\UserServiceInterface;
use Psr\Log\LoggerInterface;

readonly class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private LoggerInterface $logger
    ) {}

    public function getUsers(): array
    {
        $this->logger->info('Service: Fetching all users');
        $users = $this->repository->getUsers();
        $this->logger->info('Service: Fetched {count} users', ['count' => count($users)]);

        return $users;
    }

    public function getUser($id): ?User
    {
        $this->logger->info('Service: Fetching user {id}', ['id' => $id]);
        $user = $this->repository->getUser($id);
        $this->logger->info('Service: Fetched user {id}', ['id' => $id]);

        return $user;
    }

    public function createUser(string $name, int $age, float $money, bool $has_visa): ?User
    {
        $this->logger->info('Service: Creating user {name} age {age} money {money} visa {has_visa}', [
            'name' => $name,
            'age' => $age,
            'money' => $money,
            'has_visa' => $has_visa
        ]);

        $this->repository->addUser($name, $age, $money, $has_visa);
        $id = (int)$this->repository->getLastId();
        $user = new User($id, $name, $age, $money, $has_visa);
        $this->logger->info("Service: Created user {id}", ['id' => $id]);

        return $user;
    }

    public function updateUser(int $id, string $name, int $age, float $money, bool $has_visa): ?User
    {
        $this->logger->info('Service: Updating user {id} to name {name} age {age} money {money} visa {has_visa}',[
            'id' => $id,
            'name' => $name,
            'age' => $age,
            'money' => $money,
            'has_visa' => $has_visa
        ]);

        $this->repository->updateUser($id, $name, $age, $money, $has_visa);
        $user = new User($id, $name, $age, $money, $has_visa);
        $this->logger->info("Service: Updated user {id}", ['id' => $id]);

        return $user;
    }

    public function deleteUser(int $id): void
    {
        $this->logger->info("Service: Deleting user {id}", ['id' => $id]);

        $this->repository->deleteUser($id);
        $this->logger->info("Service Deleted user {id}", ['id' => $id]);
    }
}