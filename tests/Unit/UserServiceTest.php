<?php

declare(strict_types=1);

namespace Unit;

use App\Models\User;
use App\Services\UserRepositoryInterface;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class UserServiceTest extends TestCase
{
    private ?UserRepositoryInterface $repo = null;
    private ?UserServiceInterface $service = null;
    protected function setUp(): void
    {
        $this->repo = $this->createMock(UserRepositoryInterface::class);
        $this->service = new UserService($this->repo);
    }

    public function testGetUsersReturnsRepositoryData(): void
    {
        $expected = [
            new User(1, 'Kolya', 33, 1488, true),
            new User(2, 'Vladislave', 27, 42069, false)
        ];

        $this->repo->expects($this->once())
            ->method('getUsers')
            ->willReturn($expected);

        $result = $this->service->getUsers();

        $this->assertEquals($expected, $result);
    }

    public function testGetUserReturnUserById(): void
    {
        $testId = 3;
        $expected = new User(1, 'Zheka', 52, 666, false);

        $this->repo->expects($this->once())
            ->method('getUser')
            ->with($testId)
            ->willReturn($expected);

        $result = $this->service->getUser($testId);
        $this->assertEquals($expected, $result);
    }

    public function testDeleteUserById(): void
    {
        $testId = 51;

        $this->repo->expects($this->once())
            ->method('deleteUser')
            ->with($testId);

        $this->service->deleteUser($testId);
    }

    public function testEditUserById(): void
    {
        $testValues = [12, 'Grisha', 22, 445, true];
        $expectedUser = new User(...$testValues);

        $this->repo->expects($this->once())
            ->method('updateUser')
            ->with(...$testValues);

        $resultUser = $this->service->updateUser(...$testValues);

        $this->assertEquals($expectedUser, $resultUser);
    }

    public function testCreateUser(): void
    {
        $testValues = ['Vasya', 45, 4444, false];
        $expectedUser = new User(0, ...$testValues);
        $this->repo->expects($this->once())
            ->method('addUser')
            ->with(...$testValues);

        $resultUser = $this->service->createUser(...$testValues);

        $this->assertEquals($expectedUser, $resultUser);
    }
}