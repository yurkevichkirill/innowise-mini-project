<?php

declare(strict_types=1);

namespace App;

use App\Controllers\User\UserController;
use App\Services\UserRepository;
use App\Services\UserService;
use PDO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class App
{
    /**
     * @throws ReflectionException
     */
    public function __construct(
        protected Container $container,
        protected Router $router,
        protected array $request
    ) {
        $this->router->initializeControllers();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run ():void {
        $this->router->handler($this->request['uri'], $this->request['method']);
    }
}