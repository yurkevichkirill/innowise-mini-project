<?php

declare(strict_types=1);

namespace App;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class App
{
    /**
     * @throws \ReflectionException
     */
    public function __construct(
        private Container $container,
        private Router $router,
        private Request $request,
    ) {
        $this->router->initializeControllers();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function run(): void
    {
        $this->router->handler($this->request);
    }
}
