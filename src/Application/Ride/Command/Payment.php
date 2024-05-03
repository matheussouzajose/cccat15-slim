<?php

declare(strict_types=1);

namespace Application\Ride\Command;

class Payment
{
    public function __construct(
        private readonly string $rideId,
        private readonly string $creditCardToken,
        private readonly float $fire,
    ) {
    }

    public function getRideId(): string
    {
        return $this->rideId;
    }

    public function getCreditCardToken(): string
    {
        return $this->creditCardToken;
    }

    public function getFire(): float
    {
        return $this->fire;
    }
}
