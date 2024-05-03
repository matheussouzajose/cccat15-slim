<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Ride\Repository;

use Domain\Ride\Contracts\PositionRepositoryInterface;
use Domain\Ride\Entity\Position;
use Infrastructure\Persistence\Ride\Model\Position as Model;

class PositionRepository implements PositionRepositoryInterface
{
    public function __construct(private readonly Model $model)
    {
    }

    public function create(Position $position): void
    {
        $this->model->create(data: [
            'position_id' => $position->positionId(),
            'ride_id' => $position->rideId(),
            'latitude' => $position->latitude(),
            'longitude' => $position->longitude(),
            'created_at' => $position->createdAt(),
            'updated_at' => $position->updatedAt(),
        ]);
    }
}
