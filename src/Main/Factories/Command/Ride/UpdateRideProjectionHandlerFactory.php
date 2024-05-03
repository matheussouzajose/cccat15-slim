<?php

declare(strict_types=1);

namespace Main\Factories\Command\Ride;

use Application\Ride\Command\UpdateRideProjectionHandler;
use Main\Factories\Database\Ride\RideProjectionRepositoryFactory;

class UpdateRideProjectionHandlerFactory
{
    public static function create(): UpdateRideProjectionHandler
    {
        return new UpdateRideProjectionHandler(rideProjectionRepository: RideProjectionRepositoryFactory::create());
    }
}
