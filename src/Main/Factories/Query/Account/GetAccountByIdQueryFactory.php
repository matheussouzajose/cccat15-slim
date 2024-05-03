<?php

declare(strict_types=1);

namespace Main\Factories\Query\Account;

use Application\Account\Query\GetAccountByIdQuery;
use Main\Factories\Database\Account\AccountFactory;

class GetAccountByIdQueryFactory
{
    public static function create(): GetAccountByIdQuery
    {
        return new GetAccountByIdQuery(model: AccountFactory::create());
    }
}
