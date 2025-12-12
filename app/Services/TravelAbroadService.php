<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\travelServiceInterface;

class TravelAbroadService implements TravelServiceInterface
{
    private const array COUNTRIES_WITH_VISA = ['Poland', 'Latvia', 'Lithuania'];
    private const array COUNTRIES_WITHOUT_VISA = ['Ukraine', 'Russia'];

    public function findAvailableRoutes(User $user): array
    {
        return $user->isHasVisa() ?
            self::COUNTRIES_WITH_VISA:
            self::COUNTRIES_WITHOUT_VISA;
    }
}