<?php

declare(strict_types=1);

namespace App\Controllers\User;

use App\Attributes\Delete;
use App\Attributes\Get;
use App\Attributes\Patch;
use App\Attributes\Post;
use App\Services\UserServiceInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class UserController
{
    public function __construct(
        private UserServiceInterface $userService,
        private Environment $twig,
        private LoggerInterface $logger
    ) {}

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Get("/")]
    public function index(): void
    {
        $this->logger->info("Controller: GET / - processing request");
        echo $this->twig->render('index.twig');
        $this->logger->info("Controller: GET / - 200");
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Get("/users")]
    public function showAllUsers(): void
    {
        $this->logger->info("Controller: GET /users - processing request");
        echo $this->twig->render('users.twig', ['users' => $this->userService->getUsers()]);
        $this->logger->info("Controller: GET /users - 200");
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Get("/users/{id}")]
    public function showUser($id): void
    {
        $this->logger->info("Controller: GET /users/{id} - processing request", ['id' => $id]);
        echo $this->twig->render('user.twig', ['user' => $this->userService->getUser($id)]);
        $this->logger->info("Controller: GET /users/{id} - 200", ['id' => $id]);
    }

    #[Post("/users")]
    public function store(): void
    {
        $this->logger->info("Controller: POST /users - processing request");
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $name = $data['name'];
        $age = (int)$data['age'];
        $money = (float)$data['money'];
        $has_visa = $data['has_visa'];
        try {
            $user = $this->userService->createUser($name, $age, $money, $has_visa);
            $code = 201;
            $this->json(['user' => $user->toArray()], $code);
        } catch(Exception $e) {
            $code = 404;
            $this->json(['error' => $e->getMessage()], $code);
        } finally {
            $this->logger->info("Controller: POST /users - {code}", ['code' => $code]);
        }
    }

    #[Patch("/users/{id}")]
    public function update($id): void
    {
        $this->logger->info("Controller: PATCH /users/{id} - processing request", ['id' => $id]);
        $id = (int)$id;
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $name = $data['name'];
        $age = (int)$data['age'];
        $money = (float)$data['money'];
        $has_visa = $data['has_visa'];
        try{
            $user = $this->userService->updateUser($id, $name, $age, $money, $has_visa);
            $code = 200;
            $this->json(['user' => $user->toArray()], $code);
        } catch (Exception $e) {
            $code = 404;
            $this->json(['error' => $e->getMessage()], $code);
        } finally {
            $this->logger->info("Controller: PATCH /users/{id} - {code}", ['code' => $code]);
        }
    }

    #[Delete("/users/{id}")]
    public function remove($id): void
    {
        $this->logger->info("Controller: DELETE /users/{id} - processing request", ['id' => $id]);
        $id = (int)$id;
        try {
            $this->userService->deleteUser($id);
            $code = 204;
            $this->json([], $code);
        } catch (Exception $e) {
            $code = 404;
            $this->json(['error' => $e->getMessage()], $code);
        } finally {
            $this->logger->info("Controller: DELETE /users/{id} - {code}", ['code' => $code]);
        }
    }

    private function json(array $data, int $code): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
