<?php

declare(strict_types=1);

namespace Main\Factories\Command\Ride;

use Application\Ride\Command\StartRideHandler;
use Infrastructure\Queue\RabbitMQAdapter;
use Main\Factories\Database\Ride\RideRepositoryFactory;

class StartRideHandlerFactory
{
    public static function create(): StartRideHandler
    {
        return new StartRideHandler(rideRepository: RideRepositoryFactory::create(), queue: new RabbitMQAdapter());
    }
}
