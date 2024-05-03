<?php

declare(strict_types=1);

namespace Main\Factories\Controllers\Ride;

use Application\Ride\Command\RequestRideHandler;
use Infrastructure\Validation\RespectValidator\Ride\RequestRideValidation;
use Main\Factories\Command\Ride\RequestRideHandlerFactory;
use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Controllers\Ride\RequestRideController;
use Ui\Api\Decorators\LogControllerDecorator;

class RequestRideControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller = new RequestRideController(handler: RequestRideHandlerFactory::create(), validation: new RequestRideValidation());
        return new LogControllerDecorator(controller: $controller);
    }
}
