<?php

declare(strict_types=1);

namespace Main\Factories\Controllers\Ride;

use Infrastructure\Validation\RespectValidator\Ride\FinishRideValidation;
use Main\Factories\Command\Ride\FinishRideHandlerFactory;
use Ui\Api\Controllers\Contracts\ControllerInterface;
use Ui\Api\Controllers\Ride\FinishRideController;
use Ui\Api\Decorators\LogControllerDecorator;

class FinishRideControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller = new FinishRideController(
            handler: FinishRideHandlerFactory::create(),
            validation: new FinishRideValidation()
        );
        return new LogControllerDecorator(controller: $controller);
    }
}
