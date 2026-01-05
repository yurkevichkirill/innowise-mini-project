<?php

declare(strict_types=1);

use App\App;
use App\Container;
use App\Logger;
use App\Router;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../", ".env.test");
$dotenv->load();

$container = new Container();
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
} catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
    echo "Error $e";
} catch (ReflectionException $e) {
    echo "Error with reflection $e";
}