<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Response;
use App\Services\UserRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class WebController
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
        $html = $this->twig->render('index.twig');
        return new Response(
            $html,
            ['Content-Type' => ['text/html']],
            200
        );
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Get("/users")]
    public function showAllUsers(): ResponseInterface
    {
        $html = $this->twig->render('users.twig', ['users' => $this->userRepository->getAll()]);
        return new Response(
            $html,
            ['Content-Type' => ['text/html']],
            200
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Get("/users/{id}")]
    public function showUser($id): ResponseInterface
    {
        $html = $this->twig->render('user.twig', ['user' => $this->userRepository->get($id)]);
        return new Response(
            $html,
            ['Content-Type' => ['text/html']],
            200
        );
    }
}