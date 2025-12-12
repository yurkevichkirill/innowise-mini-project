<?php

declare(strict_types=1);

use App\App;
use App\Container;
use App\Controllers\User\UserController;
use App\DB;
use App\Router;
use App\Services\UserRepository;
use App\Services\UserService;

require_once __DIR__ . "/../vendor/autoload.php";

$container = new Container();
$router = new Router($container);

try {
    new App(
        $container,
        $router,
        ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']]
    )->run();
} catch (\Psr\Container\NotFoundExceptionInterface|\Psr\Container\ContainerExceptionInterface $e) {
    echo "Error $e";
}