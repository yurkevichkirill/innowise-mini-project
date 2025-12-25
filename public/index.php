<?php

declare(strict_types=1);

use App\App;
use App\Container;
use App\Controllers\User\UserController;
use App\DB;
use App\Logger;
use App\Router;
use App\Services\ConnectionServiceInterface;
use App\Services\UserRepository;
use App\Services\UserService;
use App\TestDB;
use Psr\Log\LoggerInterface;

require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../", ".env.test");
$dotenv->load();

$container = new Container();
$router = new Router($container);

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
$twig = new \Twig\Environment($loader, [
    'cache' => false
]);

$container->singleton(\Twig\Environment::class, $twig);

$logger = new Logger();
$container->singleton(LoggerInterface::class, $logger);

try {
    new App(
        $container,
        $router,
        ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']]
    )->run();
} catch (\Psr\Container\NotFoundExceptionInterface|\Psr\Container\ContainerExceptionInterface $e) {
    echo "Error $e";
} catch (ReflectionException $e) {
    echo "Error with reflection $e";
}