<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Request;
use App\Response;
use App\Services\UserTransformer;
use App\Services\UserRepositoryInterface;
use App\Services\UserTransformerInterface;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final readonly class WebController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private Environment             $twig
    ) {}

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Get("/")]
    public function index(): ResponseInterface
    {
        return new Response(
            $this->twig->render('index.twig'),
            ['Content-Type' => ['text/html']],
            200
        );
    }

    #[Get("/users")]
    public function showAll(Request $request): ResponseInterface
    {
        $headers = ['Content-Type' => ['text/html']];
        try {
            $users = $this->userRepository->getAll();

            return new Response(
                $this->twig->render('users.twig', ['users' => $users]),
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

    #[Get("/users/{id}")]
    public function show(Request $request): ResponseInterface
    {
        $headers = ['Content-Type' => ['text/html']];
        $segments = (explode('/', ($request->getUri()->getPath())));
        $id = (int) end($segments);
        try {
            $user = $this->userRepository->get($id);

            return new Response(
                $this->twig->render('user.twig', ['user' => $user]),
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
}