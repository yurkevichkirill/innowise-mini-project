<?php

declare(strict_types=1);

use App\App;
use App\Container;
use App\Logger;
use App\Request;
use App\Router;
use GuzzleHttp\Psr7\Uri;
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

$request = new Request(
    new Uri($_SERVER['REQUEST_URI']),
    $_SERVER['REQUEST_METHOD'],
    file_get_contents("php://input"),
    ['Accept' => ['application/json']]
);

try {
    new App(
        $container,
        $router,
        $request
    )->run();
} catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
    echo "Error $e";
} catch (ReflectionException $e) {
    echo "Error with reflection $e";
}