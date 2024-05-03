<?php

declare(strict_types=1);

namespace Main\Factories\Controllers\Ride;

use Infrastructure\Validation\RespectValidator\Ride\AcceptRideValidation;
use Main\Factories\Command\Ride\AcceptRideHandlerFactory;
use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Controllers\Ride\AcceptRideController;
use Ui\Api\Decorators\LogControllerDecorator;

class AcceptRideControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller = new AcceptRideController(handler: AcceptRideHandlerFactory::create(), validation: new AcceptRideValidation());
        return new LogControllerDecorator(controller: $controller);
    }
}
