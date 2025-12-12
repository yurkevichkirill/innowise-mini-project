<?php

declare(strict_types=1);

namespace App;

use App\Attributes\FromEnv;
use App\Services\UserRepository;
use App\Services\UserRepositoryInterface;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    private array $objects = [];
//    private array $context = [];

    public function __construct() {
        $this->objects[Services\AdultServiceInterface::class] = Services\AdultService::class;
        $this->objects[Services\UnemploymentServiceInterface::class] = Services\UnemploymentService::class;
        $this->objects[UserServiceInterface::class] = UserService::class;
        $this->objects[UserRepositoryInterface::class] = UserRepository::class;

//        $this->context = [
//            RequireClass1::class => [
//                Services\TravelAbroadService::class
//            ],
//            RequireClass2::class => [
//                Services\TravelInnerService::class
//            ]
//        ];
    }
    public function has(string $id): bool
    {
        return isset($this->objects[$id]) || class_exists($id);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws \ReflectionException
     * @throws NotFoundExceptionInterface
     */
    public function get(string $id): mixed
    {
        return $this->prepareObject($id);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws \ReflectionException
     * @throws NotFoundExceptionInterface
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
                $argumentType = $argument->getType()->getName();
                $args[$argument->getName()] = $this->get($argumentType);
            }
        }

        return new $class(...$args);
    }
}
