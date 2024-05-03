<?php

declare(strict_types=1);

namespace Ui\Api\Presenters;

use Domain\Account\Entity\Account;

class AccountPresenter
{
    public static function response(int $statusCode, object $body): HttpResponsePresenter
    {
        $isAccount = $body instanceof Account;
        return new HttpResponsePresenter(statusCode: $statusCode, body: [
            'account_id' => $isAccount ? $body->id() : $body->accountId,
            'name' => $isAccount ? $body->name() : $body->name,
            'email' => $isAccount ? $body->email() : $body->email,
            'cpf' => $isAccount ? $body->cpf() : $body->cpf,
            'car_plate' => $isAccount ? $body->carPlate() : $body->carPlate,
            'is_passenger' => $isAccount ? $body->isPassenger() : (bool)$body->isPassenger,
            'is_driver' => $isAccount ? $body->isPassenger() : (bool)$body->isDriver,
            'created_at' => $isAccount ? $body->createdAt() : (new \DateTime($body->createdAt))->format('Y-m-d H:i:s'),
            'updated_at' => $isAccount ? $body->updatedAt() : (new \DateTime($body->updatedAt))->format('Y-m-d H:i:s'),
        ]);
    }
}
