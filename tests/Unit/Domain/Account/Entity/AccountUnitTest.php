<?php

declare(strict_types=1);

namespace Unit\Domain\Account\Entity;

use Domain\Account\Entity\Account;
use PHPUnit\Framework\TestCase;

class AccountUnitTest extends TestCase
{
    public function test_should_be_create_passenger()
    {
        $account = Account::create(
            name: 'John Doe',
            email: 'jonh.doe@gmail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        $this->assertNotNull($account->accountId());
        $this->assertNotNull($account->createdAt());
        $this->assertNotNull($account->updatedAt());
        $this->assertEquals('John Doe', $account->name());
        $this->assertEquals('jonh.doe@gmail.com', $account->email());
        $this->assertEquals('97456321558', $account->cpf());
        $this->assertTrue($account->isPassenger());
        $this->assertFalse($account->isDriver());
        $this->assertNull($account->carPlate());
    }

    public function test_should_be_create_driver()
    {
        $account = Account::create(
            name: 'John Doe',
            email: 'jonh.doe@gmail.com',
            cpf: '97456321558',
            isPassenger: false,
            isDriver: true,
            carPlate: 'AMD1234'
        );
        $this->assertNotNull($account->accountId());
        $this->assertNotNull($account->createdAt());
        $this->assertNotNull($account->updatedAt());
        $this->assertEquals('John Doe', $account->name());
        $this->assertEquals('jonh.doe@gmail.com', $account->email());
        $this->assertEquals('97456321558', $account->cpf());
        $this->assertFalse($account->isPassenger());
        $this->assertTrue($account->isDriver());
        $this->assertEquals('AMD1234', $account->carPlate());
    }

    public function test_should_be_restored()
    {
        $account = Account::restore(
            accountId: '41ca3a16-c58f-4cfd-8dcd-1f1e35de909d',
            name: 'John Doe',
            email: 'jonh.doe@gmail.com',
            cpf: '97456321558',
            isPassenger: true,
            isDriver: false,
            createdAt: '2024-05-01 00:50:07',
            updatedAt: '2024-05-01 00:50:07',
            carPlate: null
        );
        $this->assertEquals('41ca3a16-c58f-4cfd-8dcd-1f1e35de909d', $account->accountId());
        $this->assertEquals('2024-05-01 00:50:07', $account->createdAt());
        $this->assertEquals('2024-05-01 00:50:07', $account->updatedAt());
        $this->assertEquals('John Doe', $account->name());
        $this->assertEquals('jonh.doe@gmail.com', $account->email());
        $this->assertEquals('97456321558', $account->cpf());
        $this->assertTrue($account->isPassenger());
        $this->assertFalse($account->isDriver());
        $this->assertNull($account->carPlate());
    }

    public function test_should_be_change_data()
    {
        $account = Account::create(
            name: 'John Doe',
            email: 'jonh.doe@gmail.com',
            cpf: '97456321558',
            isPassenger: false,
            isDriver: true,
            carPlate: 'AMD1234'
        );
        $account->changeName(name: 'John B');
        $account->changeEmail(email: 'jonh.b@gmail.com');
        $account->changeCpf(cpf: '36779082058');
        $account->changeCarPlate(carPlate: 'AMD1235');
        $this->assertEquals('John B', $account->name());
        $this->assertEquals('jonh.b@gmail.com', $account->email());
        $this->assertEquals('36779082058', $account->cpf());
        $this->assertEquals('AMD1235', $account->carPlate());
    }
}
