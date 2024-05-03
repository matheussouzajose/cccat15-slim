<?php

declare(strict_types=1);

namespace Application\Ride\Command;

use Application\Contracts\AMQPInterface;
use Domain\Ride\Contracts\RideRepositoryInterface;
use Domain\Ride\Event\Handler\QueuePublishRideStatus;
use Domain\Ride\Event\Handler\UpdateRide;
use Domain\Ride\Event\RideStartedEvent;
use Domain\Ride\Exception\RideException;

class StartRideHandler
{
    public function __construct(
        private readonly RideRepositoryInterface $rideRepository,
        private readonly AMQPInterface $queue
    ) {
    }

    public function __invoke(StartRide $command): void
    {
        $ride = $this->rideRepository->getById(id: $command->getRideId());
        if (!$ride) {
            throw RideException::notExist(id: $command->getRideId());
        }
        $ride->events()->register(
            eventName: RideStartedEvent::class,
            eventHandler: new UpdateRide(rideRepository: $this->rideRepository)
        );
        $ride->events()->register(
            eventName: RideStartedEvent::class,
            eventHandler: new QueuePublishRideStatus(queue: $this->queue)
        );
        $ride->start();
    }
}
