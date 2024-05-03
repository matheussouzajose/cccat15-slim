<?php

declare(strict_types=1);

namespace Application\Ride\Command;

use Domain\Ride\Contracts\RideProjectionRepositoryInterface;
use Domain\Ride\Exception\RideException;

class UpdateRideProjectionHandler
{
    public function __construct(private readonly RideProjectionRepositoryInterface $rideProjectionRepository)
    {
    }

    public function __invoke(UpdateRideProjection $command): void
    {
        $output = $this->rideProjectionRepository->getByRideId(rideId: $command->getRideId());
        if (!$output) {
            throw RideException::notExist(id: $command->getRideId());
        }
        $this->rideProjectionRepository->deleteByRideId(rideId: $command->getRideId());
        $this->rideProjectionRepository->create(data: $output);
    }
}
