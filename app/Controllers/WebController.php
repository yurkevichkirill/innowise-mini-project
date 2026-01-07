<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Request;
use App\Response;
use App\Services\HttpTransform;
use App\Services\UserRepositoryInterface;
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
            HttpTransform::allToHTML($this->twig, [], 'index'),
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
    public function showAllUsers(Request $request): ResponseInterface
    {
        $headers = ['Content-Type' => ['text/html']];
        $users = $this->userRepository->getAll();
        return new Response(
            HttpTransform::allToHTML($this->twig, $users, 'users'),
            $headers,
            200
        );
    }

    #[Get("/users/{id}")]
    public function showUser(Request $request): ResponseInterface
    {
        $headers = ['Content-Type' => ['text/html']];
        $id = (int)HttpTransform::getLastSegment($request->getUri()->getPath());
        try {
            $user = $this->userRepository->get($id);
            return new Response(
                HttpTransform::oneToHTML($this->twig, $user, 'user'),
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
}