<?php

declare(strict_types=1);

namespace App\Controllers\User;

use App\Attributes\Delete;
use App\Attributes\Get;
use App\Attributes\Patch;
use App\Attributes\Post;
use App\Services\UserServiceInterface;
use JetBrains\PhpStorm\NoReturn;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class UserController
{
    public function __construct(
        private UserServiceInterface $userService,
        private Environment $twig
    ) {}

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Get("/")]
    public function index(): void
    {
        echo $this->twig->render('index.twig');
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Get("/users")]
    public function showAllUsers(): void
    {
        echo $this->twig->render('users.twig', ['users' => $this->userService->getUsers()]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Get("/users/{id}")]
    public function showUser($id): void
    {
        echo $this->twig->render('user.twig', ['user' => $this->userService->getUser($id)]);
    }

    #[Post("/users")]
    public function store(): void
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $name = $data['name'];
        $age = (int)$data['age'];
        $money = (float)$data['money'];
        $has_visa = $data['has_visa'];
        try {
            $user = $this->userService->createUser($name, $age, $money, $has_visa);
            $this->json(['user' => $user->toArray()], 201);
        } catch(\Exception $e) {
            $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Patch("/users/{id}")]
    public function update($id): void
    {
        $id = (int)$id;
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $name = $data['name'];
        $age = (int)$data['age'];
        $money = (float)$data['money'];
        $has_visa = $data['has_visa'];
        try{
            $user = $this->userService->updateUser($id, $name, $age, $money, $has_visa);
            $this->json(['user' => $user->toArray()], 200);
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Delete("/users/{id}")]
    public function remove($id): void
    {
        $id = (int)$id;
        try {
            $this->userService->deleteUser($id);
            $this->json([], 204);
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 404);
        }
    }

    private function json(array $data, int $code): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
