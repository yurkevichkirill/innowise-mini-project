<?php

declare(strict_types=1);

namespace App\Services;

interface UserTransformerInterface
{
    public function transformJSON(array $users): string;
}
