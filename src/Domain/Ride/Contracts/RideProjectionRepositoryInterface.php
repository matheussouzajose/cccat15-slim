<?php

declare(strict_types=1);

namespace Domain\Ride\Contracts;

use Domain\Ride\Entity\Ride;

interface RideProjectionRepositoryInterface
{
    public function getByRideId(string $rideId): array;
    public function deleteByRideId(string $rideId): bool;

    public function create(array $data): void;
}
