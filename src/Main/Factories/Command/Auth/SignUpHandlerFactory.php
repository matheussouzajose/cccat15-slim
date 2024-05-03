<?php

declare(strict_types=1);

namespace Main\Factories\Command\Auth;

use Application\Auth\Command\SignUpHandler;
use Infrastructure\Gateway\MailerGateway;
use Main\Factories\Database\Account\AccountRepositoryFactory;

class SignUpHandlerFactory
{
    public static function create(): SignUpHandler
    {
        return new SignUpHandler(
            accountRepository: AccountRepositoryFactory::create(),
            mailerGateway: new MailerGateway()
        );
    }
}
