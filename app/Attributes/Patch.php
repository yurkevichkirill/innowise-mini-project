<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;

#[Attribute]
class Patch extends Route
{
    public function __construct(string $routePath, string $method = 'PATCH')
    {
        parent::__construct($routePath, $method);
    }
}
