<?php

declare(strict_types=1);

use App\Container;
use App\Controllers\User\UserController;
use App\DB;
use App\Router;
use App\Services\UserRepository;
use App\Services\UserService;

require_once __DIR__ . "/../vendor/autoload.php";

//$driver = getenv('POSTGRES_DRIVER');
//$host = getenv('POSTGRES_HOST');
//$port = getenv('POSTGRES_PORT');
//$dbname = getenv('DB_NAME');
//$username = getenv('POSTGRES_USER');
//$password = getenv('POSTGRES_PASSWORD');
//
//$pdo = new PDO("$driver:host=$host;port=$port;dbname=$dbname", $username, $password);

//$controller = new UserController(
//    new UserService(
//        new UserRepository(
//            new DB($pdo)
//        )
//    )
//);

$router = new Router(new Container());

try {
    $router->initializeControllers();
} catch (ReflectionException $e) {
    echo "Error $e";
}

echo "<pre>";
try {
    $router->handler('http://localhost/', 'GET');
} catch (\Psr\Container\NotFoundExceptionInterface|\Psr\Container\ContainerExceptionInterface $e) {

}
echo "</pre>";



