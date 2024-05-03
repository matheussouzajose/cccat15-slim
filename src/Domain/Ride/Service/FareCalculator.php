<?php

declare(strict_types=1);

namespace Domain\Ride\Service;

abstract class FareCalculator
{
    public function calculate(float $distance): float
    {
        return $distance * $this->getFare();
    }

    abstract public function getFare(): float;
}
