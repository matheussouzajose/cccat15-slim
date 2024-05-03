<?php

declare(strict_types=1);

namespace Domain\Ride\Event\Handler;

use Domain\Ride\Contracts\RideRepositoryInterface;
use Domain\Shared\Event\EventHandlerInterface;
use Domain\Shared\Event\EventInterface;

class UpdateRide implements EventHandlerInterface
{
    public function __construct(private readonly RideRepositoryInterface $rideRepository)
    {
    }

    public function handle(EventInterface $event): void
    {
        $ride = $event->eventData()['ride'];
        $this->rideRepository->update(ride: $ride);
    }
}
