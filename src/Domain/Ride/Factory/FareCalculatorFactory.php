<?php

declare(strict_types=1);

namespace Domain\Ride\Factory;

use Domain\Ride\Exception\FareCalculatorException;
use Domain\Ride\Service\NormalFareCalculator;
use Domain\Ride\Service\OvernightFareCalculator;
use Domain\Ride\Service\SundayFareCalculator;

class FareCalculatorFactory
{
    public static function create(\DateTimeInterface $date
    ): OvernightFareCalculator|NormalFareCalculator|SundayFareCalculator {
        $day = (int)$date->format('d');
        if ($day === 0) {
            return new SundayFareCalculator();
        }
        $hours = (int)$date->format('H');
        if ($hours > 22 || $hours < 6) {
            return new OvernightFareCalculator();
        }
        if ($hours <= 22 && $hours >= 6) {
            return new NormalFareCalculator();
        }
        throw FareCalculatorException::error();
    }
}
