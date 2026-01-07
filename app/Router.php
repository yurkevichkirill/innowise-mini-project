<?php

declare(strict_types=1);

namespace App;

use App\Attributes\Route;
use App\Controllers\APIController;
use GuzzleHttp\Psr7\Uri;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\RequestInterface;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class Router
{
    public array $routes = [];

    public function __construct(
        private readonly Container $container
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
    public function registerFromController(string $controller): void
    {
        $reflectionController = new ReflectionClass($controller);
        foreach($reflectionController->getMethods() as $method) {
            $attributes = $method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);
            foreach($attributes as $attribute) {
                $route = $attribute->newInstance();

                $this->register($route->method, $route->routePath, [$controller, $method->getName()]);
            }
        }
    }

    public function getControllerFiles(string $directory, array $controllers = []): array
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

    public function controllerFileToClass(string $controllerFile): string
    {
        return "App" . str_replace("/", "\\", str_replace(".php", "", $controllerFile));
    }

    public function register($method, $uri, $handler): void
    {
        $this->routes[$method][$uri] = $handler;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function handler(RequestInterface $request): void
    {
        $uri = parse_url($request->getUri()->getPath(), PHP_URL_PATH);
        $uri = $this->normalizePath($uri);
        $method = $request->getMethod();

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            $this->callHandler($handler, $request);
            return;
        }

        $dynamicKey = $this->createDynamicData($uri);

        $dynamicUris = preg_grep($dynamicKey, array_keys($this->routes[$method] ?? []));
        if(count($dynamicUris) === 1) {
            $dynamicUri = array_values($dynamicUris)[0];
            $call = $this->routes[$method][$dynamicUri];
            $this->callHandler($call, $request);
            return;
        }

        $this->callHandler([APIController::class, 'notFound'], $request);
    }

    private function createDynamicData(string $uri): string
    {
        $segments = explode('/', $uri);
        $segments[count($segments) - 1] = '\{\w+\}';
        return "#^" . implode('/', $segments) . "$#";
    }

    private function extractParam(array $segments): string
    {
        return end($segments);
    }

    private function normalizePath(string $path): string
    {
        $path = preg_replace('#/+#', '/', $path);
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        return $path;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundExceptionInterface
     */
    private function callHandler(array $handler, RequestInterface $request): void
    {
        [$class_name, $method_name] = $handler;
        $controller = $this->container->get($class_name);

        $response = call_user_func_array([$controller, $method_name], [$request]);

        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value");
            }
        }

        echo $response->getBody()->getContents();
    }

//    private function notFound(): void
//    {
//        http_response_code(404);
//        header('Content-Type: application/json');
//        echo json_encode(['error' => 'Not Found']);
//    }
}