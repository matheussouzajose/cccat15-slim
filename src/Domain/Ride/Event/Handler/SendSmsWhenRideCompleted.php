<?php

declare(strict_types=1);

namespace Domain\Ride\Event\Handler;

use Domain\Shared\Event\EventHandlerInterface;
use Domain\Shared\Event\EventInterface;

class SendSmsWhenRideCompleted implements EventHandlerInterface
{
    public function handle(EventInterface $event): void
    {
        $data = $event->eventData();
        var_dump("Sending sms to ..... {$data['rideId']}, {$data['creditCardToken']}, {$data['amount']}");
    }
}
