<?php

declare(strict_types=1);

namespace App;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $objects = [];

    public function __construct() {
        $this->objects[Domain\ItemRepositoryInterface::class] = DataAccess\ItemRepository::class;
        $this->objects[Domain\ItemServiceInterface::class] = Domain\ItemService::class;
        $this->objects[Domain\UserContextInterface::class] = UserInterface\UserContextInterfaceAdapter::class;
    }
    public function has(string $id): bool
    {
        return isset($this->objects[$id]) || class_exists($id);
    }
    public function get(string $id): mixed
    {
        return $this->prepareObject($id);
    }

    public function prepareObject(string $dependency): object
    {
        $dependencyReflector = new \ReflectionClass($dependency);

        if ($dependencyReflector->isInterface()) {
            $class = $this->objects[$dependency];
            $classReflector = new \ReflectionClass($class);
        } else {
            $class = $dependency;
            $classReflector = $dependencyReflector;
        }

        $constructorReflector = $classReflector->getConstructor();
        if (empty($constructorReflector)) {
            return new $class;
        }

        $constructorArguments = $constructorReflector->getParameters();
        if (empty($constructorArguments)) {
            return new $class;
        }

        $args = [];
        foreach ($constructorArguments as $argument) {
            $argumentType = $argument->getType()->getName();

            $args[$argument->getName()] = $this->get($argumentType);
        }

        return new $class(...$args);
    }
}
