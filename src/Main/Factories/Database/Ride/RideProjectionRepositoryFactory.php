<?php

declare(strict_types=1);

namespace Main\Factories\Database\Ride;

use Domain\Ride\Contracts\RideProjectionRepositoryInterface;
use Infrastructure\Persistence\Ride\Repository\RideProjectionRepository;

class RideProjectionRepositoryFactory
{
    public static function create(): RideProjectionRepositoryInterface
    {
        return new RideProjectionRepository(model: RideProjectionFactory::create());
    }
}
