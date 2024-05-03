<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Ride\ValueObject;

use Domain\Ride\Exception\CoordinateException;
use Domain\Ride\ValueObject\Coordinate;
use PHPUnit\Framework\TestCase;

class CoordinateUnitTest extends TestCase
{
    public function test_throw_an_exception_when_given_invalid_latitude()
    {
        $this->expectExceptionObject(CoordinateException::invalidLatitude());
        new Coordinate(latitude: 100, longitude: 2);
    }

    public function test_throw_an_exception_when_given_invalid_longitude()
    {
        $this->expectExceptionObject(CoordinateException::invalidLongitude());
        new Coordinate(latitude: 10, longitude: 181);
    }

    public function test_should_be_create()
    {
        $coordinate = new Coordinate(latitude: 10.00, longitude: 10.11);
        $this->assertEquals(10.00, $coordinate->latitude());
        $this->assertEquals(10.11, $coordinate->longitude());
    }
}
