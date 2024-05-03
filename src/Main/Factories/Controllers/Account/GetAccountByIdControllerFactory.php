<?php

declare(strict_types=1);

namespace Main\Factories\Controllers\Account;

use Main\Factories\Query\Account\GetAccountByIdQueryFactory;
use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Controllers\Account\GetAccountByIdController;
use Ui\Api\Decorators\LogControllerDecorator;

class GetAccountByIdControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller = new GetAccountByIdController(query: GetAccountByIdQueryFactory::create());
        return new LogControllerDecorator(controller: $controller);
    }
}
