<?php

declare(strict_types=1);

namespace Unit\Domain\Shared\ValueObject;

use Domain\Shared\Exception\UuidException;
use Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class UuidUnitTest extends TestCase
{
    public function test_can_be_random()
    {
        $uuid = Uuid::random();

        $this->assertInstanceOf(Uuid::class, $uuid);
    }

    public function test_throws_exception_error()
    {
        $expectedId = 'invalid';
        $this->expectExceptionObject(UuidException::invalid(uuid: $expectedId));

        new Uuid($expectedId);
    }

    public function test_can_be_validate()
    {
        $expectedId = (string)\Ramsey\Uuid\Uuid::uuid4();

        $uuid = new Uuid($expectedId);

        $this->assertEquals($expectedId, $uuid->value());
    }
}
