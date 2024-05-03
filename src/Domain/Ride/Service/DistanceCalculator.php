<?php

declare(strict_types=1);

namespace Domain\Ride\Service;

use Domain\Ride\ValueObject\Coordinate;

class DistanceCalculator
{
    public static function calculate(Coordinate $from, Coordinate $to)
    {
        $earthRadius = 6371;
        $degreesToRadians = pi() / 180;
        $deltaLatitude = ($to->latitude() - $from->latitude()) * $degreesToRadians;
        $deltaLongitude = ($to->longitude() - $from->longitude()) * $degreesToRadians;
        $a = sin($deltaLatitude / 2) * sin($deltaLatitude / 2) +
            cos($from->latitude() * $degreesToRadians) *
            cos($to->latitude() * $degreesToRadians) *
            sin($deltaLongitude / 2) *
            sin($deltaLongitude / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c);
    }
}
