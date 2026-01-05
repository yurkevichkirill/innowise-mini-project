<?php

declare(strict_types=1);

namespace Unit;

use App\Controllers\APIController;
use App\Models\UserDTO;
use App\Services\StreamServiceInterface;
use App\Services\UserRepositoryInterface;
use App\Services\UserServiceInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AllowMockObjectsWithoutExpectations]
class APIControllerTest extends TestCase
{
    private ?UserServiceInterface $service = null;
    private ?UserRepositoryInterface $repository = null;
    private ?StreamServiceInterface $streamService = null;
    protected function setUp(): void
    {
        $this->service = $this->createMock(UserServiceInterface::class);
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->streamService = $this->createMock(StreamServiceInterface::class);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function testGetAll(): void
    {
        $values = [
            new UserDTO(1, 'Oleg', 23, 56.1, true),
            new UserDTO(2, 'Slavik', 44, 66.6, false)
        ];

        $this->repository->method('getAll')
            ->willReturn($values);

        $controller = new APIController($this->service, $this->repository, $this->streamService);

        $result = $controller->showAll()->getBody()->getContents();
        $this->assertJson(json_encode($values), $result);
    }

    public function testGet(): void
    {
        $testId = 1;
        $testUser = new UserDTO($testId, 'Oleg', 23, 56.1, true);

        $this->repository->method('get')
            ->with($testId)
            ->willReturn($testUser);

        $controller = new APIController($this->service, $this->repository, $this->streamService);

        $result = $controller->show($testId)->getBody()->getContents();
        $this->assertJson(json_encode(['user' => $testUser->toArray()]), $result);
    }

    public function testStore(): void
    {
        $testArgs = [1, 'Oleg', 23, 56.1, true];
        $testUser = new UserDTO(...$testArgs);
        $this->service
            ->method('create')
            ->with(...$testArgs)
            ->willReturn($testUser);

        $this->streamService->method("getFromStream")
            ->with("php://input")
            ->willReturn(json_encode($testUser->toArray()));

        $controller = new APIController($this->service, $this->repository, $this->streamService);
        $result = $controller->store()->getBody()->getContents();

        $this->assertJson(json_encode(['user' => $testUser->toArray()]), $result);
    }

    public function testUpdate(): void
    {
        $testArgs = [1, 'Oleg', 23, 56.1, true];
        $testUser = new UserDTO(...$testArgs);
        $this->service
            ->method('update')
            ->with(...$testArgs)
            ->willReturn($testUser);

        $this->streamService->method("getFromStream")
            ->with("php://input")
            ->willReturn(json_encode($testUser->toArray()));

        $controller = new APIController($this->service, $this->repository, $this->streamService);
        $result = $controller->update($testArgs[0])->getBody()->getContents();

        $this->assertJson(json_encode(['user' => $testUser->toArray()]), $result);
    }

    public function testRemove(): void
    {
        $testId = 1;
        $this->repository->method('delete')
            ->with($testId);

        $controller = new APIController($this->service, $this->repository, $this->streamService);

        $result = $controller->remove($testId)->getBody()->getContents();

        $this->assertEquals(json_encode([]), $result);
    }
}