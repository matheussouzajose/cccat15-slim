<?php

declare(strict_types=1);

namespace Application\Ride\Command;

class RequestRide
{
    public function __construct(
        private readonly string $passengerId,
        private readonly float $fromLatitude,
        private readonly float $fromLongitude,
        private readonly float $toLatitude,
        private readonly float $toLongitude,
    ) {
    }

    public function getPassengerId(): string
    {
        return $this->passengerId;
    }

    public function getFromLatitude(): float
    {
        return $this->fromLatitude;
    }

    public function getFromLongitude(): float
    {
        return $this->fromLongitude;
    }

    public function getToLatitude(): float
    {
        return $this->toLatitude;
    }

    public function getToLongitude(): float
    {
        return $this->toLongitude;
    }
}
