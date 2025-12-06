<?php

declare(strict_types=1);

namespace App\Attributes;

#[\Attribute]
class Delete extends Route
{
    public function __construct(string $routePath, string $method = 'DELETE')
    {
        parent::__construct($routePath, $method);
    }
}
