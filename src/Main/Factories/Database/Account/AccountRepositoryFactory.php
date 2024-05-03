<?php

declare(strict_types=1);

namespace Main\Factories\Database\Account;

use Domain\Account\Contracts\AccountRepositoryInterface;
use Infrastructure\Persistence\Account\Repository\AccountRepository;

class AccountRepositoryFactory
{
    public static function create(): AccountRepositoryInterface
    {
        return new AccountRepository(model: AccountFactory::create());
    }
}
