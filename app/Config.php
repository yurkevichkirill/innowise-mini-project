<?php

declare(strict_types=1);

namespace App;

class Config
{
    protected array $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            'db' => [
                'host' => $env['DB_HOST'],
                'port' => $env['DB_PORT'],
                'name' => $env['DB_NAME'],
                'user' => $env['DB_USER'],
                'pass' => $env['DB_PASS'],
                'driver' => $env['DB_DRIVER'] || 'pgsql',
            ],
        ];
    }

    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }

}