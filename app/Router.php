<?php

declare(strict_types=1);

namespace App;

use App\Attributes\Route;
use App\Controllers\User\UserController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class Router
{
    public array $routes = [];

    public function __construct(
        private Container $container
    ) {}

    /**
     * @throws ReflectionException
     */
    public function initializeControllers(): void
    {
        $controllerFiles = $this->getControllerFiles("/Controllers");
        $controllerClasses = array_map(fn($controllerFile) => $this->controllerFileToClass($controllerFile), $controllerFiles);

        foreach($controllerClasses as $controller) {
            $this->registerFromController($controller);
        }
    }

    /**
     * @throws ReflectionException
     */
    public function registerFromController($controller): void
    {
        $reflectionController = new \ReflectionClass($controller);
        foreach($reflectionController->getMethods() as $method) {
            $attributes = $method->getAttributes(Route::class, \ReflectionAttribute::IS_INSTANCEOF);
            foreach($attributes as $attribute) {
                $route = $attribute->newInstance();

                $this->register($route->method, $route->routePath, [$controller, $method->getName()]);
            }
        }
    }

    public function getControllerFiles($directory, $controllers = []): array
    {
        $controllerPaths = array_diff(scandir(__DIR__ . $directory), array('.', '..'));
        $phpFiles = array_filter($controllerPaths, fn($path) => str_contains($path, ".php"));
        $phpFullFiles = array_map(fn($file) => $directory . "/" . $file, $phpFiles);
        $controllers = array_merge($controllers, $phpFullFiles);
        $folders = array_filter($controllerPaths, fn($path) => !str_contains($path, "."));
        foreach($folders as $folder) {
            $directory .= "/" . $folder;
            $controllers = $this->getControllerFiles($directory, $controllers);
        }
        return $controllers;
    }

    public function controllerFileToClass($controllerFile): string
    {
        return "App" . str_replace("/", "\\", str_replace(".php", "", $controllerFile));
    }

    public function register($method, $uri, $handler): void
    {
        $this->routes[$method][$uri] = $handler;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handler($uri, $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = $this->normalizePath($uri);

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            $this->callHandler($handler, []);
            return;
        }

        [$dynamicKey, $param] = $this->createDynamicData($uri);

        $dynamicUris = preg_grep($dynamicKey, array_keys($this->routes[$method] ?? []));
        if(count($dynamicUris) === 1) {
            $dynamicUri = array_values($dynamicUris)[0];
            $call = $this->routes[$method][$dynamicUri];
            $this->callHandler($call, [$param]);
            return;
        }

        $this->notFound();
    }

    private function createDynamicData(string $uri): array
    {
        $segments = explode('/', $uri);
        $param = $this->extractParam($segments);
        $segments[count($segments) - 1] = '\{\w+\}';
        $dynamicKey = "#^" . implode('/', $segments) . "$#";
        return array($dynamicKey, $param);
    }

    private function extractParam(array $segments): string
    {
        return end($segments);
    }

    private function normalizePath($path): string
    {
        $path = preg_replace('#/+#', '/', $path);
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        return $path;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function callHandler($handler, $params): void
    {
        [$class_name, $method_name] = $handler;
        $controller = $this->container->get($class_name);
        call_user_func_array([$controller, $method_name], $params);
    }

    private function notFound(): void
    {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not Found']);
    }
}