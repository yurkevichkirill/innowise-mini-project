<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\TransformJSONException;

final class UserTransformer implements UserTransformerInterface
{
    /**
     * @throws TransformJSONException
     */
    #[\Override]
    public function transformJSON(array $users): string
    {
        if (\count($users) === 1) {
            $usersArr = ['user' => $users[0]->toArray()];
        } else {
            $usersArr = array_map(static fn($user) => ['user' => $user->toArray()], $users);
        }
        if (json_encode($usersArr) === false) {
            throw new TransformJSONException();
        }

        return json_encode($usersArr);
    }
}
