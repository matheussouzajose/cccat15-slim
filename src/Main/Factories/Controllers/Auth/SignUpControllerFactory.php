<?php

declare(strict_types=1);

namespace Main\Factories\Controllers\Auth;


use Infrastructure\Validation\RespectValidator\Auth\SignUpValidation;
use Main\Factories\Command\Auth\SignUpHandlerFactory;
use Ui\Api\Controllers\Auth\SignUpController;
use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Decorators\LogControllerDecorator;

class SignUpControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller = new SignUpController(
            commandHandler: SignUpHandlerFactory::create(),
            validation: new SignUpValidation()
        );
        return new LogControllerDecorator(controller: $controller);
    }
}
