<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\adultServiceInterface;
use App\Models\User;

class AdultService implements AdultServiceInterface
{
    private const int ADULT_AGE = 18;

    public function isAdult(User $user): bool
    {
        return $user->getAge() >= self::ADULT_AGE;
    }
}