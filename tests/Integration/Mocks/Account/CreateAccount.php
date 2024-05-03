<?php

declare(strict_types=1);

namespace Integration\Mocks\Account;

use Domain\Shared\ValueObject\Uuid;
use Infrastructure\Database\Contracts\DbConnectionInterface;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Account\Model\Account as Model;

class CreateAccount
{
    public DbConnectionInterface $databaseConnection;
    public Model $model;
    public function __construct()
    {
        $this->databaseConnection = new MySqlDbConnectionAdapter();
        $this->databaseConnection->connectTesting();
        $this->model = new Model(databaseConnection: $this->databaseConnection);
    }

    public function create()
    {
        $id = Uuid::random()->value();
        $this->model->create([
            'account_id' => $id,
            'name' => 'Matheus Souza',
            'email' => 'matheus.souza@mail.com',
            'cpf' => '86369678058',
            'car_plate' => 'AMD1234',
            'is_passenger' => 1,
            'is_driver' => 0,
        ]);
        return $this->model->find(terms: "account_id = :account_id", params: "account_id={$id}")->fetch();
    }

    public function driver()
    {
        $id = Uuid::random()->value();
        $this->model->create([
            'account_id' => $id,
            'name' => 'Matheus Souza',
            'email' => 'matheus.souza@mail.com',
            'cpf' => '86369678058',
            'car_plate' => 'AMD1234',
            'is_passenger' => 0,
            'is_driver' => 1,
        ]);
        return $this->model->find(terms: "account_id = :account_id", params: "account_id={$id}")->fetch();
    }

    public function passenger()
    {
        $id = Uuid::random()->value();
        $this->model->create([
            'account_id' => $id,
            'name' => 'Matheus Souza',
            'email' => 'matheus.souza@mail.com',
            'cpf' => '86369678058',
            'car_plate' => null,
            'is_passenger' => 1,
            'is_driver' => 0,
        ]);
        return $this->model->find(terms: "account_id = :account_id", params: "account_id={$id}")->fetch();
    }
}
