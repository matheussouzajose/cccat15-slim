<?php

declare(strict_types=1);

namespace Main\Factories\Command\Ride;

use Application\Ride\Command\FinishRideHandler;
use Infrastructure\Mediator\Mediator;
use Infrastructure\Queue\RabbitMQAdapter;
use Main\Factories\Database\Ride\RideRepositoryFactory;

class FinishRideHandlerFactory
{
    public static function create(): FinishRideHandler
    {
        return new FinishRideHandler(
            rideRepository: RideRepositoryFactory::create(),
            queue: new RabbitMQAdapter(),
            mediator: new Mediator()
        );
    }
}
