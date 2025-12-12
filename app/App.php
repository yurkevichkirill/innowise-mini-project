<?php

declare(strict_types=1);

namespace App;

use App\Controllers\User\UserController;
use App\Services\UserRepository;
use App\Services\UserService;
use PDO;

class App
{
//    static private DB $db;
    public function __construct(
        protected Container $container,
        protected Router $router,
        array $request,
        protected Config $config
    )
    {
//        $driver = getenv('POSTGRES_DRIVER');
//        $host = getenv('POSTGRES_HOST');
//        $port = getenv('POSTGRES_PORT');
//        $dbname = getenv('DB_NAME');
//        $username = getenv('POSTGRES_USER');
//        $password = getenv('POSTGRES_PASSWORD');
//
//        $pdo = new PDO("$driver:host=$host;port=$port;dbname=$dbname", $username, $password);
//
//        $controller = new UserController(
//            new UserService(
//                new UserRepository(
//                    new DB($pdo)
//                )
//            )
//        );
    }

    public function run ():void {

    }
}