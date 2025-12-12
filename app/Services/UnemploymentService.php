<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\unemploymentServiceInterface;

class UnemploymentService implements UnemploymentServiceInterface
{
    private const LIVING_WAGE = 500;
    public function countDaysWithoutWork(User $user): int
    {
        return (int)$user->getMoney() / self::LIVING_WAGE;
    }
}