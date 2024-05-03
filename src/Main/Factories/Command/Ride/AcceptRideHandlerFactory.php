<?php

declare(strict_types=1);

namespace Main\Factories\Command\Ride;

use Application\Ride\Command\AcceptRideHandler;
use Main\Factories\Database\Account\AccountRepositoryFactory;
use Main\Factories\Database\Ride\RideRepositoryFactory;

class AcceptRideHandlerFactory
{
    public static function create(): AcceptRideHandler
    {
        return new AcceptRideHandler(
            rideRepository: RideRepositoryFactory::create(),
            accountRepository: AccountRepositoryFactory::create()
        );
    }
}
