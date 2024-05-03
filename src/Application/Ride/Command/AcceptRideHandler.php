<?php

declare(strict_types=1);

namespace Application\Ride\Command;

use Application\Account\Service\AccountService;
use Domain\Account\Contracts\AccountRepositoryInterface;
use Domain\Ride\Contracts\RideRepositoryInterface;
use Domain\Ride\Entity\Ride;
use Domain\Ride\Exception\RideException;

class AcceptRideHandler
{
    public function __construct(
        private readonly RideRepositoryInterface $rideRepository,
        private readonly AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function __invoke(AcceptRide $command): void
    {
        $ride = $this->getRide(id: $command->getRideId());
        $this->validateIsDriver(driverId: $command->getDriverId());
        $this->validateDriverHasAcceptRides(driverId: $command->getDriverId());
        $ride->accept(driverId: $command->getDriverId());
        $this->rideRepository->update($ride);
    }

    private function validateIsDriver(string $driverId): void
    {
        $accountService = new AccountService(accountRepository: $this->accountRepository);
        $accountService->validateDriverAccount(driverId: $driverId);
    }

    private function validateDriverHasAcceptRides(string $driverId): void
    {
        if (count($this->rideRepository->getAcceptRidesByDriverId(driverId: $driverId)) > 0) {
            throw RideException::driverHasAnAcceptRide();
        }
    }

    private function getRide(string $id): Ride
    {
        $ride = $this->rideRepository->getById(id: $id);
        if (!$ride) {
            throw RideException::notExist(id: $id);
        }
        return $ride;
    }
}
