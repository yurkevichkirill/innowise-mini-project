<?php

declare(strict_types=1);

namespace App\Services;

use PDO;

interface ConnectionServiceInterface
{
    public function getConnection(): PDO;
}