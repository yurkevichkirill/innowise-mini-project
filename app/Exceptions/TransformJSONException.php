<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

class TransformJSONException extends \Exception
{
    public function __construct(string $message = "Error to transform to json", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}