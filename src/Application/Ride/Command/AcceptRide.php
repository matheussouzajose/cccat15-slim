<?php

declare(strict_types=1);

namespace Application\Ride\Command;

class AcceptRide
{
    public function __construct(
        private readonly string $rideId,
        private readonly string $driverId,
    ) {
    }

    public function getRideId(): string
    {
        return $this->rideId;
    }

    public function getDriverId(): string
    {
        return $this->driverId;
    }
}
