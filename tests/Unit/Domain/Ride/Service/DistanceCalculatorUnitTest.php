<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Ride\Service;

use Domain\Ride\Service\DistanceCalculator;
use Domain\Ride\ValueObject\Coordinate;
use PHPUnit\Framework\TestCase;

class DistanceCalculatorUnitTest extends TestCase
{
    public function test_can_be_calculate()
    {
        $expectedTo = new Coordinate(latitude: 10, longitude: 20);
        $expectedFrom = new Coordinate(latitude: 30, longitude: 40);
        $distanceCalculator = DistanceCalculator::calculate(from: $expectedFrom, to: $expectedTo);

        $this->assertEquals(3041, $distanceCalculator);
    }
}
