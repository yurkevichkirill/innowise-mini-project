<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Delete;
use App\Attributes\Get;
use App\Attributes\Patch;
use App\Attributes\Post;
use App\Response;
use App\Services\HttpTransform;
use App\Services\UserRepositoryInterface;
use App\Services\UserServiceInterface;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

readonly class APIController
{
    public function __construct(
        private UserServiceInterface $userService,
        private UserRepositoryInterface $userRepository
    ) {}

    #[Get("/api/users")]
    public function showAll(RequestInterface $request): ResponseInterface
    {
        $usersObj = $this->userRepository->getAll();
        $json = HttpTransform::allToJson($usersObj);
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        return new Response(
            $json,
            $headers,
            200
        );
    }

    #[Get("/api/users/{id}")]
    public function show(RequestInterface $request): ResponseInterface
    {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        $id = (int)HttpTransform::getLastSegment($request->getUri()->getPath());
        try {
            $user = $this->userRepository->get($id);
            return new Response(
                HttpTransform::oneToJson($user),
                $headers,
                200
            );
        } catch(Exception $e) {
            return new Response(
                HttpTransform::errorToJson($e),
                $headers,
                404
            );
        }
    }

    #[Post("/api/users")]
    public function store(RequestInterface $request): ResponseInterface
    {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        $args = HttpTransform::getArgs($request->getBody()->getContents());
        try {
            $user = $this->userService->create(...$args);
            return new Response(
                HttpTransform::oneToJson($user),
                $headers,
                201
            );
        } catch(Exception $e) {
            return new Response(
                HttpTransform::errorToJson($e),
                $headers,
                404
            );
        }
    }

    #[Patch("/api/users/{id}")]
    public function update(RequestInterface $request): ResponseInterface
    {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        $args = HttpTransform::getArgs($request->getBody()->getContents());
        $id = (int) HttpTransform::getLastSegment($request->getUri()->getPath());
        try {
            $user = $this->userService->update($id, ...$args);
            return new Response(
                HttpTransform::oneToJson($user),
                $headers,
                200
            );
        } catch (Exception $e) {
            return new Response(
                HttpTransform::errorToJson($e),
                $headers,
                404
            );
        }
    }

    #[Delete("/api/users/{id}")]
    public function remove(RequestInterface $request): ResponseInterface
    {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        $id = (int) HttpTransform::getLastSegment($request->getUri()->getPath());
        try {
            $this->userRepository->delete($id);
            return new Response(
                headers: $headers,
                status: 204
            );
        } catch (Exception $e) {
            return new Response(
                HttpTransform::errorToJson($e),
                $headers,
                404
            );
        }
    }

    public function notFound(RequestInterface $request): ResponseInterface {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        return new Response(
            HttpTransform::errorToJson(new NotFoundResourceException("Resource Not Found")),
            $headers,
            404
        );
    }
}
