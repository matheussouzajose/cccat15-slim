<?php

declare(strict_types=1);

namespace Domain\Ride\Contracts;

use Domain\Ride\Entity\Ride;

interface RideRepositoryInterface
{
    public function getActiveRidesByPassengerId(string $passengerId): array;

    public function create(Ride $ride): Ride;

    public function getById(string $id): ?Ride;

    public function update(Ride $ride): void;

    public function getAcceptRidesByDriverId(string $driverId): array;
}
