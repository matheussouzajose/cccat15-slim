<?php

declare(strict_types=1);

namespace Unit\Domain\Account\ValueObject;

use Domain\Account\Exception\CarPlateException;
use Domain\Account\ValueObject\CarPlate;
use PHPUnit\Framework\TestCase;

class CarPlateUnitTest extends TestCase
{
    public function test_should_throw_an_exception()
    {
        $this->expectExceptionObject(CarPlateException::invalid(carPlate: '123246'));
        new CarPlate('123246');
    }

    public function test_should_be_create()
    {
        $name = new CarPlate(value: 'AMD1234');
        $this->assertEquals('AMD1234', $name->value());
    }
}
