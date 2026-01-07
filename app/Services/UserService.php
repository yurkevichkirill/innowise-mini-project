<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\UserNotFoundException;
use App\Models\UserDTO;
use Exception;
use Override;
use Psr\Log\LoggerInterface;

final readonly class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private LoggerInterface $logger
    ) {}

    #[Override]
    public function create(string $name, int $age, float $money, bool $has_visa): UserDTO
    {
        $user = new UserDTO(0, $name, $age, $money, $has_visa);
        return $this->repository->save($user);
    }

    /**
     * @throws Exception
     */
    #[Override]
    public function update(?int $id, ?string $name, ?int $age, ?float $money, ?bool $has_visa): UserDTO
    {
        if(!$this->repository->existUser($id)) {
            $this->logger->warning("User {id} not found", ['id' => $id]);
            throw new UserNotFoundException();
        }
        $user = new UserDTO($id, $name, $age, $money, $has_visa);
        return $this->repository->save($user);
    }
}