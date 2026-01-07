<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserDTO;
use Exception;
use Override;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class HttpTransform implements HttpTransformInterface
{
    #[Override]
    public static function allToJson(array $users): false|string
    {
        $usersArr = array_map(fn($user) => ['user' => $user->toArray()], $users);
        return json_encode($usersArr);
    }

    #[Override]
    public static function oneToJson(UserDTO $user): false|string
    {
        return json_encode(['user' => $user->toArray()]);
    }

    #[Override]
    public static function errorToJson(Exception $e): false|string
    {
        return json_encode(['error' => $e->getMessage()]);
    }

    #[Override]
    public static function getLastSegment(string $path): string
    {
        $segments = explode('/', $path);
        return end($segments);
    }

    #[Override]
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
    #[Override]
    public static function allToHTML(Environment $twig, array $users, string $name): string
    {
        return $twig->render($name .'.twig', [$name => $users]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Override]
    public static function oneToHTML(Environment $twig, UserDTO $user, string $name): string
    {
        return $twig->render($name . ".twig", [$name => $user]);
    }
}