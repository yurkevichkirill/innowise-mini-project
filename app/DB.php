<?php

declare(strict_types=1);

namespace App;

use AllowDynamicProperties;
use App\Attributes\FromEnv;
use App\Services\ConnectionServiceInterface;
use PDO;

#[AllowDynamicProperties]
class DB implements ConnectionServiceInterface
{
    public function __construct(
        #[FromEnv('DB_DSN')]
        string $dsn,
        #[FromEnv('DB_USER')]
        string $user,
        #[FromEnv('DB_PASS')]
        string $password,
    ) {
        $file = __DIR__ . "/../.env.test";
        $fileData = file_get_contents($file);
        if(str_contains($fileData, 'yes')) {
            $testDsn = getenv("TEST_DB_DSN");
            $this->pdo = new PDO($testDsn);
        } else {
            $this->pdo = new PDO($dsn, $user, $password);
        }
    }
    public function getConnection(): \PDO {
        return $this->pdo;
    }
}