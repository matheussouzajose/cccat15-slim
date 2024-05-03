<?php

declare(strict_types=1);

namespace Domain\Ride\Event\Handler;

use Application\Contracts\AMQPInterface;
use Domain\Shared\Event\EventHandlerInterface;
use Domain\Shared\Event\EventInterface;

class QueuePublishRideStatus implements EventHandlerInterface
{
    public function __construct(private readonly AMQPInterface $queue)
    {
    }

    public function handle(EventInterface $event): void
    {
        $ride = $event->eventData()['ride'];
        $this->queue->publish(queue: $event->getEventName(), payload: ['rideId' => $ride->rideId()]);
    }
}
