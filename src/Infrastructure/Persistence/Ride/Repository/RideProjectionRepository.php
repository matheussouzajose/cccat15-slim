<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Ride\Repository;

use Domain\Ride\Contracts\RideProjectionRepositoryInterface;
use Infrastructure\Persistence\Ride\Model\RideProjection;

class RideProjectionRepository implements RideProjectionRepositoryInterface
{
    public function __construct(private readonly RideProjection $model)
    {
    }

    public function getByRideId(string $rideId): array
    {
        return $this->model->getByRideId(rideId: $rideId);
    }

    public function deleteByRideId(string $rideId): bool
    {
        return $this->model->delete(terms: 'ride_id = :ride_id', params: ['ride_id' => $rideId]);
    }

    public function create(array $data): void
    {
        $this->model->create(data: $data);
    }
}
