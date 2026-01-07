<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserDTO;
use App\Services\HttpTransformInterface;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HttpTransform implements HttpTransformInterface
{
    public static function allToJson(array $users): string
    {
        $usersArr = array_map(fn($user) => ['user' => $user->toArray()], $users);
        return json_encode($usersArr);
    }

    public static function oneToJson(UserDTO $user): string
    {
        return json_encode(['user' => $user->toArray()]);
    }

    public static function errorToJson(Exception $e): string
    {
        return json_encode(['error' => $e->getMessage()]);
    }

    public static function getLastSegment(string $path): string
    {
        $segments = explode('/', $path);
        return end($segments);
    }

    public static function getArgs(string $jsonData): array
    {
        $arrData = json_decode($jsonData, true);
        $args = [];
        $args[] = $arrData['name'] ?? null;
        $args[] = $arrData['age'] ?? null;
        $args[] = $arrData['money'] ?? null;
        $args[] = $arrData['has_visa'] ?? null;

        return $args;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public static function allToHTML(Environment $twig, array $users, string $name): string
    {
        return $twig->render($name .'.twig', [$name => $users]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public static function oneToHTML(Environment $twig, UserDTO $user, string $name): string
    {
        return $twig->render($name . ".twig", [$name => $user]);
    }
}