<?php

declare(strict_types=1);

namespace App\Controllers\User;

use App\Attributes\Delete;
use App\Attributes\Get;
use App\Attributes\Post;
use App\Attributes\Put;
use App\Services\UserServiceInterface;

readonly class UserController
{
    public function __construct(private UserServiceInterface $userService) {}

    #[Get("/")]
    public function index(): void
    {
        print_r("Hello");
    }

    #[Get("/users")]
    public function showAllUsers(): void
    {
        print_r($this->userService->getUsers());
    }

    #[Get("/users/{id}")]
    public function showUser($id): void
    {
        print_r($this->userService->getUser($id));
    }

    #[Post("/")]
    public function store(): void
    {
        echo 'POST /';
    }

    #[Put("/")]
    public function update(): void
    {
        echo 'PUT /';
    }

    #[DELETE("/")]
    public function remove(): void
    {
        echo 'DELETE /';
    }
}
