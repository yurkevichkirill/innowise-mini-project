<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

interface TravelServiceInterface
{
    public function findAvailableRoutes(User $user): array;
}