<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Delete;
use App\Attributes\Get;
use App\Attributes\Patch;
use App\Attributes\Post;
use App\Response;
use App\Services\StreamServiceInterface;
use App\Services\UserRepositoryInterface;
use App\Services\UserServiceInterface;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class APIController
{
    public function __construct(
        private UserServiceInterface $userService,
        private UserRepositoryInterface $userRepository,
        private StreamServiceInterface $streamService
    ) {}

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Get("/api/users")]
    public function showAll(): ResponseInterface
    {
        $usersObj = $this->userRepository->getAll();
        $usersArr = [];
        foreach ($usersObj as $user) {
            $usersArr[] = ['user' => $user->toArray()];
        }
        $json = json_encode($usersArr);
        return new Response(
            $json,
            ['Content-Type' => ['application/json']],
            200
        );
    }

    #[Get("/api/users/{id}")]
    public function show($id): ResponseInterface
    {
        $user = $this->userRepository->get($id);
        if(!isset($user)) {
            $data = ['error' => 'User Not Found'];
            $statusCode = 404;
        } else {
            $data = ['user' => $user->toArray()];
            $statusCode = 200;
        }
        return new Response(
            json_encode($data),
            ['Content-Type' => ['application/json']],
            $statusCode
        );
    }

    #[Post("/api/users")]
    public function store(): ResponseInterface
    {
        $input = $this->streamService->getFromStream('php://input');
        $data = json_decode($input, true);
        $name = $data['name'];
        $age = (int)$data['age'];
        $money = (float)$data['money'];
        $has_visa = $data['has_visa'];
        try {
            $user = $this->userService->create($name, $age, $money, $has_visa);
            return new Response(
                json_encode(['user' => $user->toArray()]),
                ['Content-Type'  => ['application/json']],
                201
            );
        } catch(Exception $e) {
            return new Response(
                json_encode(['error' => $e->getMessage()]),
                ['Content-Type'  => ['application/json']],
                404
            );
        }
    }

    #[Patch("/api/users/{id}")]
    public function update($id): ResponseInterface
    {
        $id = (int)$id;
        $input = $this->streamService->getFromStream('php://input');
        $data = json_decode($input, true);
        $name = $data['name'] ?? null;
        $age = isset($data['age']) ? (int)$data['age'] : null;
        $money = isset($data['money']) ? (float)$data['money'] : null;
        $has_visa = $data['has_visa'] ?? null;
        try{
            $user = $this->userService->update($id, $name, $age, $money, $has_visa);
            return new Response(
                json_encode(['user' => $user->toArray()]),
                ['Content-Type'  => ['application/json']],
                200
            );
        } catch (Exception $e) {
            return new Response(
                json_encode(['error' => $e->getMessage()]),
                ['Content-Type'  => ['application/json']],
                404
            );
        }
    }

    #[Delete("/api/users/{id}")]
    public function remove($id): ResponseInterface
    {
        $id = (int)$id;
        try {
            $this->userRepository->delete($id);
            return new Response(
                json_encode([]),
                ['Content-Type'  => ['application/json']],
                204
            );
        } catch (Exception $e) {
            return new Response(
                json_encode(['error' => $e->getMessage()]),
                ['Content-Type'  => ['application/json']],
                404
            );
        }
    }
}
