<?php

declare(strict_types=1);

namespace App\Attributes;

#[\Attribute]
class Put extends Route
{
    public function __construct(string $routePath, string $method = 'PUT')
    {
        parent::__construct($routePath, $method);
    }
}
