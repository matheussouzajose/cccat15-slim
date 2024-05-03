<?php

declare(strict_types=1);

namespace Main\Factories\Database\Ride;

use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Ride\Model\Ride;

class RideFactory
{
    public static function create(): Ride
    {
        return new Ride(databaseConnection: new MySqlDbConnectionAdapter());
    }
}
