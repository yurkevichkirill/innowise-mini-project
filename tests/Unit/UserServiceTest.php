<?php

declare(strict_types=1);

namespace Unit;

use App\Models\User;
use App\Services\TestUserRepository;
use App\Services\UserRepositoryInterface;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class UserServiceTest extends TestCase
{
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

        $this->repo->expects($this->once())
            ->method('updateUser')
            ->with(...$testValues);

        $this->service->updateUser(...$testValues);
    }
}