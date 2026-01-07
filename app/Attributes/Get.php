<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;

#[Attribute]
final class Get extends Route
{
    public function __construct(string $routePath, string $method = 'GET')
    {
        parent::__construct($routePath, $method);
    }
}
