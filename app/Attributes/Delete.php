<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;

#[Attribute]
class Delete extends Route
{
    public function __construct(string $routePath, string $method = 'DELETE')
    {
        parent::__construct($routePath, $method);
    }
}
