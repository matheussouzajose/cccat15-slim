<?php

declare(strict_types=1);

namespace Application\Ride\Command;

use Application\Account\Service\AccountService;
use Domain\Account\Contracts\AccountRepositoryInterface;
use Domain\Ride\Contracts\RideRepositoryInterface;
use Domain\Ride\Entity\Ride;
use Domain\Ride\Exception\RideException;

class RequestRideHandler
{
    public function __construct(
        private readonly RideRepositoryInterface $rideRepository,
        private readonly AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function __invoke(RequestRide $command): object
    {
        $ride = Ride::create(
            passengerId: $command->getPassengerId(),
            fromLatitude: $command->getFromLatitude(),
            fromLongitude: $command->getFromLongitude(),
            toLatitude: $command->getToLatitude(),
            toLongitude: $command->getToLongitude()
        );
        $this->validateIsPassenger(passengerId: $command->getPassengerId());
        $this->validateActiveRides(passengerId: $command->getPassengerId());
        $this->rideRepository->create(ride: $ride);
        return (object)['rideId' => $ride->rideId()];
    }

    private function validateIsPassenger(string $passengerId): void
    {
        $accountService = new AccountService(accountRepository: $this->accountRepository);
        $accountService->validatePassengerAccount(passengerId: $passengerId);
    }

    private function validateActiveRides(string $passengerId): void
    {
        $activeRide = $this->rideRepository->getActiveRidesByPassengerId(passengerId: $passengerId);
        if (count($activeRide) > 0) {
            throw RideException::passengerHasAnActiveRide();
        }
    }
}
