<?php

declare(strict_types=1);

namespace Unit;

use App\Container;
use App\Controllers\User\UserController;
use App\Models\User;
use App\Router;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Twig\Environment;

class RouterTest extends TestCase
{
    public function testRegisterRoutes(): void
    {
        $container = new Container();
        $router = new Router($container);

        $router->initializeControllers();

        $this->assertArrayHasKey('GET', $router->routes);
        $this->assertArrayHasKey('/', $router->routes['GET']);
        $this->assertArrayHasKey('/users', $router->routes['GET']);
        $this->assertArrayHasKey('/users/{id}', $router->routes['GET']);
        $this->assertArrayHasKey('POST', $router->routes);
        $this->assertArrayHasKey('/users', $router->routes['POST']);
    }

    public function testHandleStaticRoutes(): void
    {
        $controller = $this->createMock(UserController::class);
        $controller->expects($this->once())
            ->method('showAllUsers');

        $controller->expects($this->once())
            ->method('index');

        $controller->expects($this->once())
            ->method('store');

        $container = $this->createMock(Container::class);
        $container->method('get')
            ->with(UserController::class)
            ->willReturn($controller);

        $router = new Router($container);
        $router->register('GET', '/users', [UserController::class, 'showAllUsers']);
        $router->register('GET', '/', [UserController::class, 'index']);
        $router->register('POST', '/users', [UserController::class, 'store']);

        $router->handler('/', 'GET');
        $router->handler('/users', 'GET');
        $router->handler('/users', 'POST');
    }

    public function testHandleDynamicRoutes(): void
    {
        $testId = 1;
        $controller = $this->createMock(UserController::class);
        $controller->expects($this->once())
            ->method('showUser')
            ->with($testId);

        $controller->expects($this->once())
            ->method('update')
            ->with($testId);

        $controller->expects($this->once())
            ->method('remove')
            ->with($testId);

        $container = $this->createMock(Container::class);
        $container->method('get')
            ->with(UserController::class)
            ->willReturn($controller);

        $router = new Router($container);
        $router->register('GET', '/users/{id}', [UserController::class, 'showUser']);
        $router->register('PATCH', '/users/{id}', [UserController::class, 'update']);
        $router->register('DELETE', '/users/{id}', [UserController::class, 'remove']);

        $router->handler("/users/$testId", 'GET');
        $router->handler("/users/$testId", 'PATCH');
        $router->handler("/users/$testId", 'DELETE');
    }

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