<?php

declare(strict_types=1);

namespace Domain\Ride\Service;

class NormalFareCalculator extends FareCalculator
{
    public const FARE = 2.4;
    public function getFare(): float
    {
        return self::FARE;
    }
}
