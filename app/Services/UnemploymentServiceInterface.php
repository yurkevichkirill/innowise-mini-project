<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

interface UnemploymentServiceInterface
{
    public function countDaysWithoutWork(User $user): int;
}