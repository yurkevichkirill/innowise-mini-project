<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserDTO;
use Exception;
use Twig\Environment;

interface UserTransformerInterface
{
    public function transformJSON(array $users): string;

}