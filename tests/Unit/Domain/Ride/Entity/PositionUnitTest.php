<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Ride\Entity;

use Domain\Ride\Entity\Position;
use PHPUnit\Framework\TestCase;

class PositionUnitTest extends TestCase
{
    public function test_should_be_create()
    {
        $position = Position::create(
            rideId: 'f59734a2-cd08-4bc8-a3d2-15265c9f6f11',
            latitude: 30.00,
            longitude: 10.00
        );
        $this->assertNotNull($position->positionId());
        $this->assertNotNull($position->createdAt());
        $this->assertNotNull($position->updatedAt());
        $this->assertEquals('f59734a2-cd08-4bc8-a3d2-15265c9f6f11', $position->rideId());
        $this->assertEquals(30.00, $position->latitude());
        $this->assertEquals(10.00, $position->longitude());
    }

    public function test_should_be_restored()
    {
        $position = Position::restore(
            positionId: 'f59734a2-cd08-4bc8-a3d2-15265c9f6f11',
            rideId: 'f59734a2-cd08-4bc8-a3d2-15265c9f6f12',
            latitude: 10.00,
            longitude: 11.11,
            createdAt: '2024-05-01 02:06:05',
            updatedAt: '2024-05-01 02:06:05'
        );
        $this->assertEquals('f59734a2-cd08-4bc8-a3d2-15265c9f6f11', $position->positionId());
        $this->assertEquals('f59734a2-cd08-4bc8-a3d2-15265c9f6f12', $position->rideId());
        $this->assertEquals(10.00, $position->latitude());
        $this->assertEquals(11.11, $position->longitude());
        $this->assertEquals('2024-05-01 02:06:05', $position->createdAt());
        $this->assertEquals('2024-05-01 02:06:05', $position->updatedAt());
    }
}
