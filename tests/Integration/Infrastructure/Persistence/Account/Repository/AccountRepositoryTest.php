<?php

declare(strict_types=1);

namespace Integration\Infrastructure\Persistence\Account\Repository;

use Domain\Account\Contracts\AccountRepositoryInterface;
use Domain\Account\Entity\Account;
use Infrastructure\Database\Contracts\DbConnectionInterface;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Account\Model\Account as Model;
use Infrastructure\Persistence\Account\Repository\AccountRepository;
use Integration\Mocks\Account\CreateAccount;
use Tests\DatabaseTestCase;

class AccountRepositoryTest extends DatabaseTestCase
{
    public DbConnectionInterface $databaseConnection;
    public Model $model;
    public AccountRepositoryInterface $accountRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->databaseConnection = new MySqlDbConnectionAdapter();
        $this->databaseConnection->connectTesting();
        $this->model = new Model(databaseConnection: $this->databaseConnection);
        $this->accountRepository = new AccountRepository(model: $this->model);
    }

    public function test_should_be_true_when_exist_account()
    {
        $output = $this->accountRepository->existEmail(email: 'invalid@mail.com');

        $this->assertFalse($output);
    }

    public function test_should_be_false_when_exist_account()
    {
        $accountModel = (new CreateAccount())->create();
        $output = $this->accountRepository->existEmail(email: $accountModel->email);

        $this->assertTrue($output);
    }

    public function test_can_be_created()
    {
        $expectedName = 'Matheus Souza';
        $expectedEmail = 'matheus.jose@maddddil.com';
        $expectedCpf = '86369678058';
        $account = Account::create(
            name: $expectedName,
            email: $expectedEmail,
            cpf: $expectedCpf,
            isPassenger: true,
            isDriver: false
        );
        $output = $this->accountRepository->create(account: $account);

        $this->assertNotNull($output->accountId());
        $this->assertNotNull($output->createdAt());
        $this->assertNotNull($output->updatedAt());
        $this->assertEquals($expectedName, $output->name());
        $this->assertEquals($expectedEmail, $output->email());
        $this->assertEquals($expectedCpf, $output->cpf());
        $this->assertTrue($output->isPassenger());
        $this->assertFalse($output->isDriver());
        $this->assertNull($output->carPlate());
    }

    public function test_should_return_null_when_get_by_id_return_null()
    {
        $output = $this->accountRepository->getById(id: 'invalid');

        $this->assertNull($output);
    }

    public function test_should_return_account_when_get_by_id_return_account()
    {
        $accountModel = (new CreateAccount())->create();
        $output = $this->accountRepository->getById(id: $accountModel->account_id);

        $this->assertNotNull($output->accountId());
    }
}
