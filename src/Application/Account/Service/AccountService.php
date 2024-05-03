<?php

declare(strict_types=1);

namespace Application\Account\Service;

use Domain\Account\Contracts\AccountRepositoryInterface;
use Domain\Account\Exception\AccountException;

class AccountService
{
    public function __construct(private readonly AccountRepositoryInterface $accountRepository)
    {
    }

    public function validateDriverAccount(string $driverId): void
    {
        $account = $this->accountRepository->getById(id: $driverId);
        if (!$account) {
            throw AccountException::notFound(id: $driverId);
        }
        if (!$account->isDriver()) {
            throw AccountException::notDriver();
        }
    }

    public function validatePassengerAccount(string $passengerId): void
    {
        $account = $this->accountRepository->getById(id: $passengerId);
        if (!$account) {
            throw AccountException::notFound(id: $passengerId);
        }
        if (!$account->isPassenger()) {
            throw AccountException::notPassenger();
        }
    }
}
