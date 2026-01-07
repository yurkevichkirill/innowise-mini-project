<?php

declare(strict_types=1);

namespace Unit;

use App\Controllers\APIController;
use App\Models\UserDTO;
use App\Request;
use App\Services\StreamServiceInterface;
use App\Services\UserRepositoryInterface;
use App\Services\UserServiceInterface;
use GuzzleHttp\Psr7\Uri;
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
    protected function setUp(): void
    {
        $this->service = $this->createMock(UserServiceInterface::class);
        $this->repository = $this->createMock(UserRepositoryInterface::class);
    }

    public function testGetAll(): void
    {
        $values = [
            new UserDTO(1, 'Oleg', 23, 56.1, true),
            new UserDTO(2, 'Slavik', 44, 66.6, false)
        ];

        $this->repository->method('getAll')
            ->willReturn($values);

        $controller = new APIController($this->service, $this->repository);

        $getRequest = new Request(
            uri: new Uri('/api/users'),
            method: 'GET',
            headers: ['Accept' => ['application/json']]
        );
        $result = $controller->showAll($getRequest)->getBody()->getContents();
        $this->assertJson(json_encode($values), $result);
    }

    public function testGet(): void
    {
        $testId = 1;
        $testUser = new UserDTO($testId, 'Oleg', 23, 56.1, true);

        $this->repository->method('get')
            ->with($testId)
            ->willReturn($testUser);

        $controller = new APIController($this->service, $this->repository);

        $getRequest = new Request(
            uri: new Uri("/api/users/$testId"),
            method: 'GET',
            headers: ['Accept' => ['application/json']]
        );
        $result = $controller->show($getRequest)->getBody()->getContents();
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

        $postRequest = new Request(
            uri: new Uri('/api/users'),
            method: 'POST',
            body: json_encode(
                $testUser->toArray()
            ),
            headers: ['Accept' => ['application/json']]
        );
        $controller = new APIController($this->service, $this->repository);
        $result = $controller->store($postRequest)->getBody()->getContents();

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

        $controller = new APIController($this->service, $this->repository);

        $patchRequest = new Request(
            uri: new Uri("/api/users/1"),
            method: 'PATCH',
            body: json_encode(
                $testUser->toArray()
            ),
            headers: ['Accept' => ['application/json']]
        );

        $result = $controller->update($patchRequest)->getBody()->getContents();

        $this->assertJson(json_encode(['user' => $testUser->toArray()]), $result);
    }

    public function testRemove(): void
    {
        $testId = 1;
        $this->repository->method('delete')
            ->with($testId);

        $controller = new APIController($this->service, $this->repository);

        $deleteRequest = new Request(
            uri: new Uri("/api/users/$testId"),
            method: 'DELETE',
            headers: ['Accept' => ['application/json']]
        );
        $result = $controller->remove($deleteRequest)->getBody()->getContents();

        $this->assertEquals('', $result);
    }
}