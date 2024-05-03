<?php

declare(strict_types=1);

namespace Integration\Infrastructure\Mediator;

use Infrastructure\Mediator\Mediator;
use Infrastructure\Mediator\RideStartedObserver;
use Infrastructure\Mediator\RideStartedObserver2;
use PHPUnit\Framework\TestCase;

class Mediator2Test extends TestCase
{
    public function test_()
    {
        $mediator = Mediator::events();
        $mediator->attach(observer: new RideStartedObserver(), event: 'rideStarted');
        $mediator->attach(observer: new RideStartedObserver2(), event: 'rideStarted');

        $mediator->trigger(event: 'rideStarted');
    }
}
