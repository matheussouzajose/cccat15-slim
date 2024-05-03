<?php

declare(strict_types=1);

namespace Main\Factories\Database\Account;

use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Account\Model\Account;

class AccountFactory
{
    public static function create(): Account
    {
        return new Account(databaseConnection: new MySqlDbConnectionAdapter());
    }
}
