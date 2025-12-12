<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\travelServiceInterface;

class TravelInnerService implements TravelServiceInterface
{
    private const BELARUS_REGIONS = ['Minsk', 'Gomel', 'Brest', 'Vitebsk', 'Mogilev', 'Grodno'];

    public function findAvailableRoutes(User $user): array
    {
        return self::BELARUS_REGIONS;
    }
}