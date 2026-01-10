<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Delete;
use App\Attributes\Get;
use App\Attributes\Patch;
use App\Attributes\Post;
use App\Request;
use App\Response;
use App\Services\UserTransformer;
use App\Services\UserRepositoryInterface;
use App\Services\UserServiceInterface;
use App\Services\UserTransformerInterface;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * @psalm-suppress ClassMustBeFinal
 */
readonly class APIController
{
    public function __construct(
        private UserServiceInterface $userService,
        private UserRepositoryInterface $userRepository,
        private UserTransformerInterface $userTransformer
    ) {}

    #[Get("/api/users")]
    public function showAll(Request $request): Response
    {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        try {
            $usersObj = $this->userRepository->getAll();
            $json = $this->userTransformer->transformJSON($usersObj);

            return new Response(
                $json,
                $headers,
                200
            );
        } catch(Exception $e) {
            return new Response(
                json_encode(['error' => $e->getMessage()]),
                $headers,
                404
            );
        }
    }

    #[Get("/api/users/{id}")]
    public function show(Request $request): Response
    {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        $segments = (explode('/', ($request->getUri()->getPath())));
        $id = (int) end($segments);
        try {
            $user = $this->userRepository->get($id);

            return new Response(
                $this->userTransformer->transformJSON([$user]),
                $headers,
                200
            );
        } catch (Exception $e) {

            return new Response(
                json_encode(['error' => $e->getMessage()]),
                $headers,
                404
            );
        }
    }

    #[Post("/api/users")]
    public function store(Request $request): Response
    {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        $args = $request->getArgs();
        try {
            $user = $this->userService->create(...$args);

            return new Response(
                $this->userTransformer->transformJSON([$user]),
                $headers,
                201
            );
        } catch(Exception $e) {

            return new Response(
                json_encode(['error' => $e->getMessage()]),
                $headers,
                404
            );
        }
    }

    #[Patch("/api/users/{id}")]
    public function update(Request $request): Response
    {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        $args = $request->getArgs();
        $segments = (explode('/', ($request->getUri()->getPath())));
        $id = (int) end($segments);
        try {
            $user = $this->userService->update($id, ...$args);

            return new Response(
                $this->userTransformer->transformJSON([$user]),
                $headers,
                200
            );
        } catch (Exception $e) {

            return new Response(
                json_encode(['error' => $e->getMessage()]),
                $headers,
                404
            );
        }
    }

    #[Delete("/api/users/{id}")]
    public function remove(Request $request): Response
    {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];
        $segments = (explode('/', ($request->getUri()->getPath())));
        $id = (int) end($segments);
        try {
            $this->userRepository->delete($id);

            return new Response(
                headers: $headers,
                status: 204
            );
        } catch (Exception $e) {

            return new Response(
                json_encode(['error' => $e->getMessage()]),
                $headers,
                404
            );
        }
    }

    public function notFound(Request $request): Response {
        $headers = ['Content-Type' => [$request->getHeaderLine('Accept')]];

        return new Response(
            json_encode(['error' => new NotFoundResourceException("Resource Not Found")->getMessage()]),
            $headers,
            404
        );
    }
}
//php-csfixer
//phpcbf
