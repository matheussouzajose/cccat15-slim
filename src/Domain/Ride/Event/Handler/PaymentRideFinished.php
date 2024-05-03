<?php

declare(strict_types=1);

namespace Domain\Ride\Event\Handler;

use Application\Ride\Command\Payment;
use Application\Ride\Command\PaymentHandler;
use Domain\Shared\Event\EventHandlerInterface;
use Domain\Shared\Event\EventInterface;

class PaymentRideFinished implements EventHandlerInterface
{
    public function __construct(private readonly PaymentHandler $paymentHandler)
    {
    }

    public function handle(EventInterface $event): void
    {
        $ride = $event->eventData()['ride'];
        $creditCard = $event->eventData()['creditCardToken'];
        $inputPayment = new Payment(rideId: $ride->rideId(), creditCardToken: $creditCard, fire: $ride->fare());
        ($this->paymentHandler)(command: $inputPayment);
    }
}
