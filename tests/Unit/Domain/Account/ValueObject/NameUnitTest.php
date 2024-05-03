<?php

declare(strict_types=1);

namespace Unit\Domain\Account\ValueObject;

use Domain\Account\Exception\NameException;
use Domain\Account\ValueObject\Name;
use PHPUnit\Framework\TestCase;

class NameUnitTest extends TestCase
{
    public function test_should_throw_an_exception()
    {
        $this->expectExceptionObject(NameException::invalid(name: 'John'));
        new Name('John');
    }

    public function test_should_be_create()
    {
        $name = new Name(value: 'John Doe');
        $this->assertEquals('John Doe', $name->value());
    }
}
