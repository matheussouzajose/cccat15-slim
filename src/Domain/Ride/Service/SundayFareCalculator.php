<?php

declare(strict_types=1);

namespace Domain\Ride\Service;

class SundayFareCalculator extends FareCalculator
{
    public const FARE = 2.9;

    public function getFare(): float
    {
        return self::FARE;
    }
}
