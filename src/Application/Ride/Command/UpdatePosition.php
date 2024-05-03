<?php

declare(strict_types=1);

namespace Application\Ride\Command;

class UpdatePosition
{
    public function __construct(
        private readonly string $rideId,
        private readonly float $latitude,
        private readonly float $longitude
    ) {
    }

    public function getRideId(): string
    {
        return $this->rideId;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
