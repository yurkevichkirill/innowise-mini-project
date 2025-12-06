<?php

declare(strict_types=1);

namespace App\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::IS_REPEATABLE)]
class Route
{
    public function __construct(
        public string $routePath,
        public string $method='GET'
    ){}
}
