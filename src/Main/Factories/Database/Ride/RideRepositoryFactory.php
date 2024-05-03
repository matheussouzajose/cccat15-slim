<?php

declare(strict_types=1);

namespace Main\Factories\Database\Ride;

use Domain\Ride\Contracts\RideRepositoryInterface;
use Infrastructure\Persistence\Ride\Repository\RideRepository;

class RideRepositoryFactory
{
    public static function create(): RideRepositoryInterface
    {
        return new RideRepository(model: RideFactory::create());
    }
}
