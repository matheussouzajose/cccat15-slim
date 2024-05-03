<?php

declare(strict_types=1);

namespace Integration\Application\Account\Query;

use Application\Account\Query\GetAccountByIdQuery;
use Application\Auth\Command\SignUp;
use Application\Auth\Command\SignUpHandler;
use Domain\Account\Exception\AccountException;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Account\Model\Account;
use Infrastructure\Persistence\Account\Repository\AccountRepository;
use Tests\DatabaseTestCase;
use Tests\Stubs\MailerGatewayStub;

class GetAccountByIdQueryTest extends DatabaseTestCase
{
    public GetAccountByIdQuery $getAccountByIdQuery;
    public SignUpHandler $signUpHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $mysql = new MySqlDbConnectionAdapter();
        $mysql->connectTesting();
        $model = new Account(databaseConnection: $mysql);
        $accountRepository = new AccountRepository(model: $model);
        $this->getAccountByIdQuery = new GetAccountByIdQuery(model: $model);
        $this->signUpHandler = new SignUpHandler(
            accountRepository: $accountRepository,
            mailerGateway: new MailerGatewayStub()
        );
    }

    public function test_should_throw_an_exception_when_account_not_found()
    {
        $this->expectExceptionObject(AccountException::notFound(id: 'invalid_id'));
        ($this->getAccountByIdQuery)(accountId: 'invalid_id');
    }

    public function test_should_return_account()
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
        $this->assertNull($outputGetAccountByIdQuery->carPlate);
        $this->assertFalse($outputGetAccountByIdQuery->isDriver);
        $this->assertNotNull($outputGetAccountByIdQuery->createdAt);
        $this->assertNotNull($outputGetAccountByIdQuery->updatedAt);
    }
}
