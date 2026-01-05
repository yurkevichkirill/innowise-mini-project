<?php

declare(strict_types=1);

namespace App\Services;

class StreamService implements StreamServiceInterface
{
    public function getFromStream(string $stream): string
    {
        return file_get_contents($stream);
    }

}