<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

interface AdultServiceInterface
{
    public function isAdult(User $user): bool;
}