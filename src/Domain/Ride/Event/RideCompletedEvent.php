<?php

declare(strict_types=1);

namespace Domain\Ride\Event;

use Domain\Ride\Entity\Ride;
use Domain\Shared\Event\EventInterface;

class RideCompletedEvent implements EventInterface
{
    public function __construct(
        private readonly Ride $ride,
        private readonly string $creditCardToken
    ) {
    }

    public function dateTimeOccurred(): \DateTimeInterface
    {
        return new \DateTime();
    }

    public function eventData(): array
    {
        return [
            'ride' => $this->ride,
            'creditCardToken' => $this->creditCardToken
        ];
    }

    public function getEventName(): string
    {
        return 'rideCompleted';
    }
}
