<?php

declare(strict_types=1);

namespace App\Controllers\User;

use App\Attributes\Delete;
use App\Attributes\Get;
use App\Attributes\Post;
use App\Attributes\Put;

class UserController extends \App\Controller
{
    #[Get("/")]
    public function index(): void
    {
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com']
        ];

        $this->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    #[Get("/users/{id}")]
    public function show($id): void
    {
        $user = [
            'id' => $id,
            'name' => 'User ' . $id,
            'email' => "user{$id}@example.com"
        ];

        $this->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    #[Post("/")]
    public function store(): void
    {
        $input = $this->input();

        $user = [
            'id' => rand(1000, 9999),
            'name' => $input['name'] ?? 'New User',
            'email' => $input['email'] ?? 'new@example.com'
        ];

        $this->json([
            'status' => 'success',
            'message' => 'User created',
            'data' => $user
        ], 201);
    }

    #[Put("/")]
    public function update(): void
    {

    }

    #[DELETE("/")]
    public function remove(): void
    {

    }
}
