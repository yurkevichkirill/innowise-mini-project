<?php

declare(strict_types=1);

namespace App;

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
        protected Request $request
    ) {
        $this->router->initializeControllers();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function run ():void {
        $this->router->handler($this->request);
    }
}