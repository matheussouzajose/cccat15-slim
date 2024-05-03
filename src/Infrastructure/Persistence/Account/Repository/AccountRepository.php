<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Account\Repository;

use Domain\Account\Entity\Account;
use Domain\Account\Contracts\AccountRepositoryInterface;
use Infrastructure\Persistence\Account\Model\Account as Model;

class AccountRepository implements AccountRepositoryInterface
{
    public function __construct(private readonly Model $model)
    {
    }

    public function existEmail(string $email): bool
    {
        return !!$this->model->find(terms: "email = :email", params: "email={$email}")->fetch();
    }

    /**
     * @throws \Exception
     */
    public function create(Account $account): Account
    {
        $this->model->create([
            'account_id' => $account->accountId(),
            'name' => $account->name(),
            'email' => $account->email(),
            'cpf' => $account->cpf(),
            'car_plate' => $account->carPlate(),
            'is_passenger' => (int)$account->isPassenger(),
            'is_driver' => (int)$account->isDriver(),
            'created_at' => $account->createdAt(),
            'updated_at' => $account->updatedAt(),
        ]);
        $result = $this->model->find(terms: "account_id = :account_id", params: "account_id={$account->accountId()}")->fetch();
        return Account::restore(
            accountId: $result->account_id,
            name: $result->name,
            email: $result->email,
            cpf: $result->cpf,
            isPassenger: (bool)$result->is_passenger,
            isDriver: (bool)$result->is_driver,
            createdAt: $result->created_at,
            updatedAt: $result->updated_at,
            carPlate: $result->car_plate
        );
    }

    /**
     * @throws \Exception
     */
    public function getById(string $id): ?Account
    {
        $account = $this->model->find(terms: "account_id = :account_id", params: "account_id={$id}")->fetch();
        if (!$account) {
            return null;
        }
        return Account::restore(
            accountId: $account->account_id,
            name: $account->name,
            email: $account->email,
            cpf: $account->cpf,
            isPassenger: (bool)$account->is_passenger,
            isDriver: (bool)$account->is_driver,
            createdAt: $account->created_at,
            updatedAt: $account->updated_at,
            carPlate: $account->car_plate
        );
    }
}
