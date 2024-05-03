<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Ride\Service;

use Domain\Ride\Service\NormalFareCalculator;
use Domain\Ride\Service\OvernightFareCalculator;
use Domain\Ride\Service\SundayFareCalculator;
use PHPUnit\Framework\TestCase;

class FareCalculatorUnitTest extends TestCase
{
    public function test_can_be_normal()
    {
        $normalFareCalculator = new NormalFareCalculator();
        $result = $normalFareCalculator->calculate(distance: 10);

        $this->assertEquals(24, $result);
    }

    public function test_can_be_overnight()
    {
        $overnightFareCalculator = new OvernightFareCalculator();
        $result = $overnightFareCalculator->calculate(distance: 10);

        $this->assertEquals(39, $result);
    }

    public function test_can_be_sunday()
    {
        $sundayFareCalculator = new SundayFareCalculator();
        $result = $sundayFareCalculator->calculate(distance: 10);

        $this->assertEquals(29, $result);
    }
}
