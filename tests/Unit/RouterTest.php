<?php

declare(strict_types=1);

namespace Unit;

use App\Container;
use App\Controllers\APIController;
use App\Router;
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

        $router->handler('/api/users', 'GET');
        $router->handler('/api/users', 'POST');
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testHandleDynamicRoutes(): void
    {
        $testId = 1;
        $controller = $this->createMock(APIController::class);
        $controller->expects($this->once())
            ->method('show')
            ->with($testId);

        $controller->expects($this->once())
            ->method('update')
            ->with($testId);

        $controller->expects($this->once())
            ->method('remove')
            ->with($testId);

        $container = $this->createMock(Container::class);
        $container->method('get')
            ->with(APIController::class)
            ->willReturn($controller);

        $router = new Router($container);
        $router->register('GET', '/api/users/{id}', [APIController::class, 'show']);
        $router->register('PATCH', '/api/users/{id}', [APIController::class, 'update']);
        $router->register('DELETE', '/api/users/{id}', [APIController::class, 'remove']);

        $router->handler("/api/users/$testId", 'GET');
        $router->handler("/api/users/$testId", 'PATCH');
        $router->handler("/api/users/$testId", 'DELETE');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundExceptionInterface
     */
    public function testHandleUnknownRoute(): void
    {
        $container = $this->createStub(Container::class);
        $router = new Router($container);

        ob_start();
        $router->handler('/unknown', 'GET');
        $output = ob_get_clean();

        $this->assertJsonStringEqualsJsonString(
            '{"error":"Not Found"}',
            $output
        );
    }
}