<?php

declare(strict_types=1);

namespace Unit;

use App\Models\UserDTO;
use App\Services\UserRepositoryInterface;
use App\Services\UserService;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[AllowMockObjectsWithoutExpectations]
final class UserServiceTest extends TestCase
{
    private ?UserRepositoryInterface $repo = null;

    private ?LoggerInterface $logger = null;

    protected function setUp(): void
    {
        $this->repo = $this->createMock(UserRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testCreateUser(): void
    {
        $testArgs = ['Kolya', 33, 1_488, true];
        $testValue = new UserDTO(0, ...$testArgs);

        $this->repo->method('save')
            ->with($testValue)
            ->willReturn($testValue);

        $service = new UserService($this->repo, $this->logger);
        $result = $service->create(...$testArgs);

        self::assertEquals($testValue, $result);
    }

    /**
     * @throws \Exception
     */
    public function testUpdateUser(): void
    {
        $testArgs = [1, 'Kolya', 33, 1_488, true];
        $testValue = new UserDTO(...$testArgs);

        $this->repo->method('save')
            ->with($testValue)
            ->willReturn($testValue);

        $this->repo->method('existUser')
            ->with($testArgs[0])
            ->willReturn(true);

        $service = new UserService($this->repo, $this->logger);
        $result = $service->update(...$testArgs);

        self::assertEquals($testValue, $result);
    }
}
