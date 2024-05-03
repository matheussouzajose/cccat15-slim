<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Ride\Entity;

use Domain\Ride\Entity\Ride;
use Domain\Ride\Enum\Status;
use Domain\Ride\Exception\StatusException;
use PHPUnit\Framework\TestCase;

class RideUnitTest extends TestCase
{
    public function test_should_create()
    {
        $ride = Ride::create(
            passengerId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb558',
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858
        );
        $this->assertNotNull($ride->rideId());
        $this->assertNotNull($ride->createdAt());
        $this->assertNotNull($ride->updatedAt());
        $this->assertEquals(-27.584905257812, $ride->fromLatitude());
        $this->assertEquals(-48.54502219545, $ride->fromLongitude());
        $this->assertEquals(-27.496887588311, $ride->toLatitude());
        $this->assertEquals(-48.522234807858, $ride->toLongitude());
        $this->assertEquals(-27.584905257812, $ride->lastLatitude());
        $this->assertEquals(-48.54502219545, $ride->lastLongitude());
        $this->assertEquals("requested", $ride->status());
        $this->assertEquals(0, $ride->distance());
        $this->assertEquals(0, $ride->fare());
        $this->assertNull($ride->driverId());
    }

    public function test_should_be_restored()
    {
        $ride = Ride::restore(
            rideId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb558',
            passengerId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb559',
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858,
            lastLatitude: -27.584905257812,
            lastLongitude: -48.54502219545,
            status: 'requested',
            distance: 0.0,
            fare: 0.0,
            createdAt: '2024-05-01 02:06:05',
            updatedAt: '2024-05-01 02:06:05',
            driverId: null
        );
        $this->assertEquals('4f9c0bb2-2d90-496c-a0be-6ca3c66bb558', $ride->rideId());
        $this->assertEquals('2024-05-01 02:06:05', $ride->createdAt());
        $this->assertEquals('2024-05-01 02:06:05', $ride->updatedAt());
        $this->assertEquals(-27.584905257812, $ride->fromLatitude());
        $this->assertEquals(-48.54502219545, $ride->fromLongitude());
        $this->assertEquals(-27.496887588311, $ride->toLatitude());
        $this->assertEquals(-48.522234807858, $ride->toLongitude());
        $this->assertEquals(-27.584905257812, $ride->lastLatitude());
        $this->assertEquals(-48.54502219545, $ride->lastLongitude());
        $this->assertEquals('requested', $ride->status());
        $this->assertEquals(0.0, $ride->distance());
        $this->assertEquals(0.0, $ride->fare());
        $this->assertNull($ride->driverId());
    }

    public function test_should_throw_an_exception_when_invalid_accept()
    {
        $ride = Ride::create(
            passengerId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb558',
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858,
        );
        $ride->accept(driverId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb559');
        $this->expectExceptionObject(StatusException::accepted());
        $ride->accept(driverId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb559');
    }

    public function test_should_be_accept()
    {
        $ride = Ride::create(
            passengerId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb558',
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858,
        );
        $ride->accept(driverId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb559');
        $this->assertEquals('4f9c0bb2-2d90-496c-a0be-6ca3c66bb559', $ride->driverId());
        $this->assertEquals(Status::ACCEPTED->value, $ride->status());
    }

    public function test_should_throw_an_exception_when_invalid_start()
    {
        $this->expectExceptionObject(StatusException::started());
        $ride = Ride::create(
            passengerId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb558',
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858,
        );
        $ride->start();
    }

    public function test_should_be_start()
    {
        $ride = Ride::create(
            passengerId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb558',
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858,
        );
        $ride->accept(driverId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb559');
        $ride->start();
        $this->assertEquals(Status::IN_PROGRESS->value, $ride->status());
    }

    public function test_should_throw_an_exception_when_invalid_update_position()
    {
        $this->expectExceptionObject(StatusException::updatePosition());
        $ride = Ride::create(
            passengerId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb558',
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858,
        );
        $ride->updatePosition(latitude: -27.584905257812, longitude: -27.584905257812);
    }

    public function test_should_be_update_position()
    {
        $ride = Ride::create(
            passengerId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb558',
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858,
        );
        $ride->accept(driverId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb559');
        $ride->start();
        $ride->updatePosition(latitude: -27.584905257822, longitude: -27.584905257811);
        $this->assertEquals(2063, $ride->distance());
        $this->assertEquals(-27.584905257822, $ride->lastLatitude());
        $this->assertEquals(-27.584905257811, $ride->lastLongitude());
    }

    public function test_should_throw_an_exception_when_invalid_finish()
    {
        $this->expectExceptionObject(StatusException::finish());
        $ride = Ride::create(
            passengerId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb558',
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858,
        );
        $ride->finish();
    }

    public function test_should_be_update_finish()
    {
        $ride = Ride::create(
            passengerId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb558',
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858,
        );
        $ride->accept(driverId: '4f9c0bb2-2d90-496c-a0be-6ca3c66bb559');
        $ride->start();
        $ride->updatePosition(latitude: -27.584905257822, longitude: -27.584905257811);
        $ride->finish();
        $this->assertEquals(4951.2, $ride->fare());
        $this->assertEquals(Status::COMPLETED->value, $ride->status());
    }
}
