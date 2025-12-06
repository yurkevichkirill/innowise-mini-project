<?php

declare(strict_types=1);

namespace App\Attributes;

#[\Attribute]
class Post extends Route
{
    public function __construct(string $routePath, string $method = 'POST')
    {
        parent::__construct($routePath, $method);
    }
}
