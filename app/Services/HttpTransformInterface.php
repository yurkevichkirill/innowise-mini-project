<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserDTO;
use Exception;
use Twig\Environment;

interface HttpTransformInterface
{
    public static function allToJson(array $users): false|string;
    public static function oneToJson(UserDTO $user): false|string;
    public static function errorToJson(Exception $e): false|string;
    public static function getLastSegment(string $path): string;
    public static function getArgs(string $jsonData): array;
    public static function allToHTML(Environment $twig, array $users, string $name): string;
    public static function oneToHTML(Environment $twig, UserDTO $user, string $name): string;

}