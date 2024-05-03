<?php

declare(strict_types=1);

namespace Application\Ride\Command;

class UpdateRideProjection
{
    public function __construct(private readonly string $rideId)
    {
    }

    public function getRideId(): string
    {
        return $this->rideId;
    }
}
