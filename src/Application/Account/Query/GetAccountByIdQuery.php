<?php

declare(strict_types=1);

namespace Application\Account\Query;

use Domain\Account\Exception\AccountException;
use Infrastructure\Persistence\Model;

class GetAccountByIdQuery
{
    public function __construct(private readonly Model $model)
    {
    }

    public function __invoke(string $accountId): object
    {
        $account = $this->model->find(terms: "account_id = :account_id", params: "account_id={$accountId}")->fetch();
        if (!$account) {
            throw AccountException::notFound(id: $accountId);
        }
        return $this->output(account: $account);
    }

    private function output(object $account): object
    {
        return (object)[
            'accountId' => $account->account_id,
            'name' => $account->name,
            'email' => $account->email,
            'cpf' => $account->cpf,
            'carPlate' => $account->car_plate,
            'isPassenger' => (bool)$account->is_passenger,
            'isDriver' => (bool)$account->is_driver,
            'createdAt' => $account->created_at,
            'updatedAt' => $account->updated_at,
        ];
    }
}
