<?php

declare(strict_types=1);

namespace Application\Ride\Command;

use Application\Contracts\AMQPInterface;
use Domain\Ride\Contracts\RideRepositoryInterface;
use Domain\Ride\Event\Handler\QueuePublishRideStatus;
use Domain\Ride\Event\Handler\UpdateRide;
use Domain\Ride\Event\RideCompletedEvent;
use Domain\Ride\Exception\RideException;
use Infrastructure\Mediator\Mediator;

class FinishRideHandler
{
    public function __construct(
        private readonly RideRepositoryInterface $rideRepository,
        private readonly AMQPInterface $queue,
        private readonly Mediator $mediator
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(FinishRide $command): void
    {
        $ride = $this->rideRepository->getById(id: $command->getRideId());
        if (!$ride) {
            throw RideException::notExist(id: $command->getRideId());
        }
        $ride->events()->register(
            eventName: RideCompletedEvent::class,
            eventHandler: new UpdateRide(rideRepository: $this->rideRepository)
        );
        $ride->events()->register(
            eventName: RideCompletedEvent::class,
            eventHandler: new QueuePublishRideStatus(queue: $this->queue)
        );
        $ride->finish();
    }
}
