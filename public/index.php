<?php

declare(strict_types=1);

use App\App;
use App\Container;
use App\Logger;
use App\Router;
use App\Services\ConnectionServiceInterface;
use App\TestDB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . "/../vendor/autoload.php";

$container = new Container();

$headers = $_SERVER;
$test_mode = $_SERVER['HTTP_X_TEST_MODE'] ?? false;
if($test_mode) {
    $container->bind(ConnectionServiceInterface::class, TestDB::class);
}

$router = new Router($container);

$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader, [
    'cache' => false
]);

$container->singleton(Environment::class, $twig);

$logger = new Logger();
$container->singleton(LoggerInterface::class, $logger);

try {
    new App(
        $container,
        $router,
        ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']]
    )->run();
} catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
    echo "Error $e";
} catch (ReflectionException $e) {
    echo "Error with reflection $e";
}