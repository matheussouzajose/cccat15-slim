<?php

declare(strict_types=1);

namespace Main\Factories\Command\Ride;

use Application\Ride\Command\RequestRideHandler;
use Main\Factories\Database\Account\AccountRepositoryFactory;
use Main\Factories\Database\Ride\RideRepositoryFactory;

class RequestRideHandlerFactory
{
    public static function create(): RequestRideHandler
    {
        return new RequestRideHandler(
            rideRepository: RideRepositoryFactory::create(),
            accountRepository: AccountRepositoryFactory::create()
        );
    }
}
