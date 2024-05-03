<?php

declare(strict_types=1);

namespace Domain\Ride\Service;

class OvernightFareCalculator extends FareCalculator
{
    public const FARE = 3.9;

    public function getFare(): float
    {
        return self::FARE;
    }
}
