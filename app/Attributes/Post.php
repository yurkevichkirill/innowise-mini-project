<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;

#[Attribute]
final class Post extends Route
{
    public function __construct(string $routePath, string $method = 'POST')
    {
        parent::__construct($routePath, $method);
    }
}
