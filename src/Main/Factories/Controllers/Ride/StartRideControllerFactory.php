<?php

declare(strict_types=1);

namespace Main\Factories\Controllers\Ride;

use Infrastructure\Validation\RespectValidator\Ride\StartRideValidation;
use Main\Factories\Command\Ride\StartRideHandlerFactory;
use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Controllers\Ride\StartRideController;
use Ui\Api\Decorators\LogControllerDecorator;

class StartRideControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller = new StartRideController(
            handler: StartRideHandlerFactory::create(),
            validation: new StartRideValidation()
        );
        return new LogControllerDecorator(controller: $controller);
    }
}
