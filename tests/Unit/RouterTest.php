<?php

declare(strict_types=1);

namespace Unit;

use App\Container;
use App\Controllers\APIController;
use App\Request;
use App\Router;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

#[AllowMockObjectsWithoutExpectations]
class RouterTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testRegisterRoutes(): void
    {
        $container = new Container();
        $router = new Router($container);

        $router->initializeControllers();

        $this->assertArrayHasKey('GET', $router->routes);
        $this->assertArrayHasKey('/api/users', $router->routes['GET']);
        $this->assertArrayHasKey('/api/users/{id}', $router->routes['GET']);
        $this->assertArrayHasKey('POST', $router->routes);
        $this->assertArrayHasKey('/api/users', $router->routes['POST']);
        $this->assertArrayHasKey('PATCH', $router->routes);
        $this->assertArrayHasKey('/api/users/{id}', $router->routes['PATCH']);
        $this->assertArrayHasKey('DELETE', $router->routes);
        $this->assertArrayHasKey('/api/users/{id}', $router->routes['DELETE']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testHandleStaticRoutes(): void
    {
        $controller = $this->createMock(APIController::class);
        $controller->expects($this->once())
            ->method('showAll');

        $controller->expects($this->once())
            ->method('store');

        $container = $this->createMock(Container::class);
        $container->method('get')
            ->with(APIController::class)
            ->willReturn($controller);

        $router = new Router($container);
        $router->register('GET', '/api/users', [APIController::class, 'showAll']);
        $router->register('POST', '/api/users', [APIController::class, 'store']);

        $getRequest = new Request(
            uri: new Uri('/api/users'),
            method: 'GET',
            headers: ['Accept' => ['application/json']]
        );
        $router->handler($getRequest);

        $postRequest = new Request(
            uri: new Uri('/api/users'),
            method: 'POST',
            headers: ['Accept' => ['application/json']]
        );
        $router->handler($postRequest);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testHandleDynamicRoutes(): void
    {
        $testId = 1;
        $getRequest = new Request(
            uri: new Uri("/api/users/$testId"),
            method: 'GET',
            headers: ['Accept' => ['application/json']]
        );

        $patchRequest = new Request(
            uri: new Uri("/api/users/$testId"),
            method: 'PATCH',
            headers: ['Accept' => ['application/json']]
        );

        $deleteRequest = new Request(
            uri: new Uri("/api/users/$testId"),
            method: 'DELETE',
            headers: ['Accept' => ['application/json']]
        );
        $controller = $this->createMock(APIController::class);
        $controller->expects($this->once())
            ->method('show')
            ->with($getRequest);

        $controller->expects($this->once())
            ->method('update')
            ->with($patchRequest);

        $controller->expects($this->once())
            ->method('remove')
            ->with($deleteRequest);

        $container = $this->createMock(Container::class);
        $container->method('get')
            ->with(APIController::class)
            ->willReturn($controller);

        $router = new Router($container);
        $router->register('GET', '/api/users/{id}', [APIController::class, 'show']);
        $router->register('PATCH', '/api/users/{id}', [APIController::class, 'update']);
        $router->register('DELETE', '/api/users/{id}', [APIController::class, 'remove']);

        $router->handler($getRequest);
        $router->handler($patchRequest);
        $router->handler($deleteRequest);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundExceptionInterface
     */
    public function testHandleUnknownRoute(): void
    {
        $request = new Request(
            uri: new Uri("/unknown"),
            method: 'GET',
            headers: ['Accept' => ['application/json']]
        );

        $controller = $this->createMock(APIController::class);
        $controller->expects($this->once())
            ->method('notFound')
            ->with($request);

        $container = $this->createMock(Container::class);
        $container->method('get')
            ->with(APIController::class)
            ->willReturn($controller);

        $router = new Router($container);

        $router->handler($request);
    }
}