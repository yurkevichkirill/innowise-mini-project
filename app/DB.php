<?php

declare(strict_types=1);

namespace App;

use AllowDynamicProperties;
use App\Attributes\FromEnv;

#[AllowDynamicProperties]
class DB
{
    public function __construct(
        #[FromEnv('DB_DSN')]
        string $dsn,
        #[FromEnv('DB_USER')]
        string $user,
        #[FromEnv('DB_PASS')]
        string $password,
    ) {
        $this->pdo = new \PDO($dsn, $user, $password);
    }
    public function getConnection(): \PDO {
        return $this->pdo;
    }
}