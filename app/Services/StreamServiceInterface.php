<?php

declare(strict_types=1);

namespace App\Services;

interface StreamServiceInterface
{
    public function getFromStream(string $stream): string;
}