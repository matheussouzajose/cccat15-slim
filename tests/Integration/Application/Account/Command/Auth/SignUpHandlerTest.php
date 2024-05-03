<?php

declare(strict_types=1);

namespace Integration\Application\Account\Command\Auth;

use Application\Account\Query\GetAccountByIdQuery;
use Application\Auth\Command\SignUp;
use Application\Auth\Command\SignUpHandler;
use Domain\Account\Contracts\AccountRepositoryInterface;
use Domain\Account\Exception\AccountException;
use Domain\Account\Exception\CarPlateException;
use Domain\Account\Exception\CpfException;
use Domain\Account\Exception\EmailException;
use Domain\Account\Exception\NameException;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Account\Model\Account as Model;
use Infrastructure\Persistence\Account\Repository\AccountRepository;
use Tests\DatabaseTestCase;
use Tests\Stubs\MailerGatewayStub;

class SignUpHandlerTest extends DatabaseTestCase
{
    public SignUpHandler $signUpHandler;
    public GetAccountByIdQuery $getAccountByIdQuery;

    protected function setUp(): void
    {
        parent::setUp();
        $mysql = new MySqlDbConnectionAdapter();
        $mysql->connectTesting();
        $model = new Model(databaseConnection: $mysql);
        $accountRepository = new AccountRepository(model: $model);
        $this->signUpHandler = new SignUpHandler(
            accountRepository: $accountRepository,
            mailerGateway: new MailerGatewayStub()
        );
        $this->getAccountByIdQuery = new GetAccountByIdQuery(model: $model);
    }

    public function test_should_be_not_create_passenger_account_if_email_already_exists()
    {
        $inputSignUp = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        ($this->signUpHandler)(command: $inputSignUp);
        $this->expectExceptionObject(AccountException::alreadyExist());
        ($this->signUpHandler)(command: $inputSignUp);
    }

    public function test_should_be_create_passenger_account()
    {
        $inputSignUp = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUp);
        $outputGetAccountByIdQuery = ($this->getAccountByIdQuery)(accountId: $outputSignUp->accountId);
        $this->assertEquals($outputSignUp->accountId, $outputGetAccountByIdQuery->accountId);
        $this->assertEquals('John Doe', $outputGetAccountByIdQuery->name);
        $this->assertEquals('jonh.doe@mail.com', $outputGetAccountByIdQuery->email);
        $this->assertEquals('97456321558', $outputGetAccountByIdQuery->cpf);
        $this->assertTrue($outputGetAccountByIdQuery->isPassenger);
    }

    public function test_should_be_create_driver_account()
    {
        $inputSignUp = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: false,
            isDriver: true,
            carPlate: 'AMD1234'
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUp);
        $outputGetAccountByIdQuery = ($this->getAccountByIdQuery)(accountId: $outputSignUp->accountId);
        $this->assertEquals($outputSignUp->accountId, $outputGetAccountByIdQuery->accountId);
        $this->assertEquals('John Doe', $outputGetAccountByIdQuery->name);
        $this->assertEquals('jonh.doe@mail.com', $outputGetAccountByIdQuery->email);
        $this->assertEquals('97456321558', $outputGetAccountByIdQuery->cpf);
        $this->assertEquals('AMD1234', $outputGetAccountByIdQuery->carPlate);
        $this->assertTrue($outputGetAccountByIdQuery->isDriver);
    }

    public function test_should_be_not_create_passenger_account_if_given_invalid_name()
    {
        $this->expectExceptionObject(NameException::invalid(name: 'John'));
        $inputSignUp = new SignUp(
            name: 'John',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        ($this->signUpHandler)(command: $inputSignUp);
    }

    public function test_should_be_not_create_passenger_account_if_given_invalid_email()
    {
        $this->expectExceptionObject(EmailException::invalid(email: 'jonh.doemail.com'));
        $inputSignUp = new SignUp(
            name: 'John Doe',
            email: 'jonh.doemail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        ($this->signUpHandler)(command: $inputSignUp);
    }

    public function test_should_be_not_create_passenger_account_if_given_invalid_cpf()
    {
        $this->expectExceptionObject(CpfException::invalid(cpf: '123456789'));
        $inputSignUp = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '123456789',
            isPassenger: true
        );
        ($this->signUpHandler)(command: $inputSignUp);
    }

    public function test_should_be_not_create_driver_account_if_given_invalid_car_plate()
    {
        $this->expectExceptionObject(CarPlateException::invalid(carPlate: '1234'));
        $inputSignUp = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: false,
            isDriver: true,
            carPlate: '1234'
        );
        ($this->signUpHandler)(command: $inputSignUp);
    }
}
