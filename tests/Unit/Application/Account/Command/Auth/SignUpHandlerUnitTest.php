<?php

declare(strict_types=1);

namespace Unit\Application\Account\Command\Auth;

use Application\Auth\Command\SignUp;
use Application\Auth\Command\SignUpHandler;
use Application\Contracts\MailerGatewayInterface;
use Domain\Account\Contracts\AccountRepositoryInterface;
use Domain\Account\Entity\Account;
use Domain\Account\Exception\EmailException;
use PHPUnit\Framework\TestCase;

class SignUpHandlerUnitTest extends TestCase
{
    public function test_throw_error_when_account_already_exists()
    {
        $expectedEmail = 'matheus.souza@gmail.com';

        $this->expectExceptionObject(EmailException::alreadyExist(email: $expectedEmail));
        $accountRepository = $this->mockAccountRepository(existEmail: true);
        $mailerGateway = $this->mockMailerGateway(0);
        $commandHandler = new SignUpHandler(accountRepository: $accountRepository, mailerGateway: $mailerGateway);
        $command = new SignUp(
            name: 'Matheus Souza',
            email: 'matheus.souza@gmail.com',
            cpf: '98923525057',
            isPassenger: true,
            isDriver: false
        );
        ($commandHandler)(command: $command);
    }

    public function test_can_be_created()
    {
        $expectedName = 'Matheus Souza';
        $expectedEmail = 'matheus.souza@gmail.com';
        $expectedCpf = '98923525057';

        $accountRepository = $this->mockAccountRepository(existEmail: false);
        $mailerGateway = $this->mockMailerGateway();
        $commandHandler = new SignUpHandler(accountRepository: $accountRepository, mailerGateway: $mailerGateway);
        $command = new SignUp(
            name: $expectedName,
            email: $expectedEmail,
            cpf: $expectedCpf,
            isPassenger: true,
            isDriver: false
        );
        $output = ($commandHandler)(command: $command);

        $this->assertNotNull($output->id());
        $this->assertNotNull($output->createdAt());
        $this->assertNotNull($output->updatedAt());
        $this->assertEquals($expectedName, $output->name());
        $this->assertEquals($expectedEmail, $output->email());
        $this->assertEquals($expectedCpf, $output->cpf());
        $this->assertTrue($output->isPassenger());
        $this->assertFalse($output->isDriver());
    }

    private function mockAccountRepository(bool $existEmail): AccountRepositoryInterface
    {
        $mockery = \Mockery::mock(\stdClass::class, AccountRepositoryInterface::class);
        $mockery->shouldReceive('existEmail')->andReturn($existEmail);
        $mockery->shouldReceive('create')->andReturn($this->createAccount());
        return $mockery;
    }

    private function mockMailerGateway(int $timesCallAction = 1): MailerGatewayInterface
    {
        $mockery = \Mockery::mock(\stdClass::class, MailerGatewayInterface::class);
        $mockery->shouldReceive('send')->times($timesCallAction);
        return $mockery;
    }

    private function createAccount(): Account
    {
        return Account::create(
            name: 'Matheus Souza',
            email: 'matheus.souza@gmail.com',
            cpf: '98923525057',
            isPassenger: true,
            isDriver: false
        );
    }
}
