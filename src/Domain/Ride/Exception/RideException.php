<?php

declare(strict_types=1);

namespace Domain\Ride\Exception;

class RideException extends \InvalidArgumentException
{
    public static function passengerHasAnActiveRide(): RideException
    {
        return new self(message: 'Passenger has an active ride', code: 422);
    }

    public static function driverHasAnAcceptRide(): RideException
    {
        return new self(message: 'Driver has an accept ride', code: 422);
    }

    public static function notExist(string $id): RideException
    {
        $message = sprintf('Ride %s does not exist', $id);
        return new self(message: $message, code: 404);
    }
}
