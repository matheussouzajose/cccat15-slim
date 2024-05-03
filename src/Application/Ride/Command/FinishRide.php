<?php

declare(strict_types=1);

namespace Application\Ride\Command;

class FinishRide
{
    public function __construct(private readonly string $rideId)
    {
    }

    public function getRideId(): string
    {
        return $this->rideId;
    }
}
