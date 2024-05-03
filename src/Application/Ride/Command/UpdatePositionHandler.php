<?php

declare(strict_types=1);

namespace Application\Ride\Command;

use Domain\Ride\Contracts\PositionRepositoryInterface;
use Domain\Ride\Contracts\RideRepositoryInterface;
use Domain\Ride\Entity\Position;
use Domain\Ride\Exception\RideException;

class UpdatePositionHandler
{
    public function __construct(
        private readonly RideRepositoryInterface $rideRepository,
        private readonly PositionRepositoryInterface $positionRepository
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdatePosition $command): void
    {
        $ride = $this->rideRepository->getById(id: $command->getRideId());
        if (!$ride) {
            throw RideException::notExist(id: $command->getRideId());
        }
        $ride->updatePosition(latitude: $command->getLatitude(), longitude: $command->getLongitude());
        $this->rideRepository->update(ride: $ride);
        $position = Position::create(
            rideId: $command->getRideId(),
            latitude: $command->getLatitude(),
            longitude: $command->getLongitude()
        );
        $this->positionRepository->create(position: $position);
    }
}
