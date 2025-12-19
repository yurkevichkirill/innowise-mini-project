<?php

declare(strict_types=1);

use App\App;
use App\Container;
use App\Controllers\User\UserController;
use App\DB;
use App\Router;
use App\Services\ConnectionServiceInterface;
use App\Services\UserRepository;
use App\Services\UserService;
use App\TestDB;

require_once __DIR__ . "/../vendor/autoload.php";

$container = new Container();
//if(getenv('TEST_MODE') === 'yes') {
//    $container->bind(ConnectionServiceInterface::class, TestDB::class);
//    putenv('TEST_MODE=no');
//}
$router = new Router($container);

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
$twig = new \Twig\Environment($loader, [
    'cache' => false
]);

$container->singleton(\Twig\Environment::class, $twig);

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