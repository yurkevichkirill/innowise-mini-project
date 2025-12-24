<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class FromEnv
{
    public function __construct(
        public string $key,
        public ?string $default = null,
    ) {}
}