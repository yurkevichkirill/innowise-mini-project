<?php

declare(strict_types=1);

namespace App;

use App\Attributes\FromEnv;
use App\Exceptions\ContainerException;
use App\Services\ConnectionServiceInterface;
use App\Services\UserRepository;
use App\Services\UserRepositoryInterface;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use Override;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

final class Container implements ContainerInterface
{
    private array $objects = [];
    private array $singletons = [];

    public function __construct() {
        $this->objects[UserServiceInterface::class] = UserService::class;
        $this->objects[UserRepositoryInterface::class] = UserRepository::class;
        $this->objects[ConnectionServiceInterface::class] = DB::class;
    }

    #[Override]
    public function has(string $id): bool
    {
        return isset($this->objects[$id]) || isset($this->singletons[$id]) || class_exists($id);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException|ContainerException
     */
    #[Override]
    public function get(string $id): mixed
    {
        if(isset($this->singletons[$id])) {
            return $this->singletons[$id];
        }

        return $this->prepareObject($id);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerException
     */
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
            $fromEnvs = $argument->getAttributes(FromEnv::class);
            if(!empty($fromEnvs)) {
                $attribute = $fromEnvs[0];
                $envName = $attribute->getArguments()[0];
                $args[$argument->getName()] = getenv($envName);
            } else {
                $type = $argument->getType();
                if (!$type) {
                    throw new ContainerException("Parameter '{$argument->getName()}' has no type");
                }

                if (!$type instanceof \ReflectionNamedType) {
                    throw new ContainerException("UnionType not supported for '{$argument->getName()}'");
                }

                $argumentType = $type->getName();
                $args[$argument->getName()] = $this->get($argumentType);
            }
        }

        return new $class(...$args);
    }

    public function singleton(string $id, object $instance): void
    {
        $this->singletons[$id] = $instance;
    }

    public function bind(string $id, string $class): void
    {
        $this->objects[$id] = $class;
    }
}
