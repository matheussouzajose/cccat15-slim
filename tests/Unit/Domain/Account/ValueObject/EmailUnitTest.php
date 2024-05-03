<?php

declare(strict_types=1);

namespace Unit\Domain\Account\ValueObject;

use Domain\Account\Exception\EmailException;
use Domain\Account\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailUnitTest extends TestCase
{
    public function test_should_throw_an_exception()
    {
        $this->expectExceptionObject(EmailException::invalid(email: 'john.com.br'));
        new Email('john.com.br');
    }

    public function test_should_be_create()
    {
        $name = new Email(value: 'john.doe@mail.com.br');
        $this->assertEquals('john.doe@mail.com.br', $name->value());
    }
}
