<?php

declare(strict_types=1);

namespace Domain\Account\Contracts;

use Domain\Account\Entity\Account;

interface AccountRepositoryInterface
{
    public function existEmail(string $email): bool;

    public function create(Account $account): Account;

    public function getById(string $id): ?Account;
}
