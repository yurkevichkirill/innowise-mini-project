<?php

declare(strict_types=1);

namespace App;

use AllowDynamicProperties;
use App\Attributes\FromEnv;
use App\Services\ConnectionServiceInterface;

#[AllowDynamicProperties]
class TestDB implements ConnectionServiceInterface
{
    public function __construct(
        #[FromEnv('TEST_DB_DSN')]
        string $dsn
    )
    {
        $this->pdo = new \PDO($dsn);
    }

    public function getConnection(): \PDO
    {
        return $this->pdo;
    }
}