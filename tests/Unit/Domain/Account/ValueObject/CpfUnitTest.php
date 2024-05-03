<?php

declare(strict_types=1);

namespace Unit\Domain\Account\ValueObject;

use Domain\Account\Exception\CpfException;
use Domain\Account\ValueObject\Cpf;
use PHPUnit\Framework\TestCase;

class CpfUnitTest extends TestCase
{
    public function test_should_throw_an_exception()
    {
        $this->expectExceptionObject(CpfException::invalid(cpf: '123246'));
        new Cpf('123246');
    }

    public function test_should_be_create()
    {
        $cpf = new Cpf(value: '92202536078');
        $this->assertEquals('92202536078', $cpf->value());
    }
}
