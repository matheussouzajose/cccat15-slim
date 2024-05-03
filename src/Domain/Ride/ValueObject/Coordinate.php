<?php

declare(strict_types=1);

namespace Domain\Ride\ValueObject;

use Domain\Ride\Exception\CoordinateException;

class Coordinate
{
    private readonly float $latitude;
    private readonly float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->ensureIsValid(latitude: $latitude, longitude: $longitude);
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    private function ensureIsValid(float $latitude, float $longitude): void
    {
        if ($latitude < -90 || $latitude > 90) {
            throw CoordinateException::invalidLatitude();
        }
        if ($longitude < -180 || $longitude > 180)  {
            throw CoordinateException::invalidLongitude();
        }
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }
}
