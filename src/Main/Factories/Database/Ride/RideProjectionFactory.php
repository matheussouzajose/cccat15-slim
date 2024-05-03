<?php

declare(strict_types=1);

namespace Main\Factories\Database\Ride;

use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Ride\Model\RideProjection;

class RideProjectionFactory
{
    public static function create(): RideProjection
    {
        return new RideProjection(databaseConnection: new MySqlDbConnectionAdapter());
    }
}
