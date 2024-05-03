<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Ride\Repository;

use Domain\Ride\Contracts\RideRepositoryInterface;
use Domain\Ride\Entity\Ride;
use Infrastructure\Persistence\Ride\Model\Ride as Model;

class RideRepository implements RideRepositoryInterface
{
    public function __construct(private readonly Model $model)
    {
    }

    private function outputRides(array $rides): array
    {
        $outputs = [];
        foreach ($rides as $ride) {
            $outputs[] = $this->restore(data: $ride);
        }
        return $outputs;
    }

    public function getActiveRidesByPassengerId(string $passengerId): array
    {
        $rides = $this->model->find(
            terms: "passenger_id = :passenger_id and status = :status",
            params: "passenger_id={$passengerId}&status=requested"
        )->fetchAll();
        if (!$rides) {
            return [];
        }
        return $this->outputRides(rides: $rides);
    }

    public function create(Ride $ride): Ride
    {
        $this->model->create([
            'ride_id' => $ride->rideId(),
            'passenger_id' => $ride->passengerId(),
            'driver_id' => $ride->driverId(),
            'fare' => $ride->fare(),
            'distance' => $ride->distance(),
            'status' => $ride->status(),
            'from_lat' => $ride->fromLatitude(),
            'from_long' => $ride->fromLongitude(),
            'to_lat' => $ride->toLatitude(),
            'to_long' => $ride->toLongitude(),
            'last_lat' => $ride->lastLatitude(),
            'last_long' => $ride->lastLongitude(),
            'created_at' => $ride->createdAt(),
            'updated_at' => $ride->updatedAt(),
        ]);
        $result = $this->model->find(terms: "ride_id = :ride_id", params: "ride_id={$ride->rideId()}")->fetch();
        return $this->restore(data: $result);
    }

    private function restore(object $data): Ride
    {
        return Ride::restore(
            rideId: $data->ride_id,
            passengerId: $data->passenger_id,
            fromLatitude: (float)$data->from_lat,
            fromLongitude: (float)$data->from_long,
            toLatitude: (float)$data->to_lat,
            toLongitude: (float)$data->to_long,
            lastLatitude: (float)$data->last_lat,
            lastLongitude: (float)$data->last_long,
            status: $data->status,
            distance: (float)$data->distance,
            fare: (float)$data->fare,
            createdAt: $data->created_at,
            updatedAt: $data->updated_at,
            driverId: $data->driver_id,
        );
    }

    public function getById(string $id): ?Ride
    {
        $result = $this->model->find(terms: "ride_id = :ride_id", params: "ride_id={$id}")->fetch();
        if (!$result) {
            return null;
        }
        return $this->restore(data: $result);
    }

    public function update(Ride $ride): void
    {
        $this->model->update(data: [
//            'passenger_id' => $ride->passengerId(),
            'driver_id' => $ride->driverId(),
//            'fare' => $ride->fare(),
//            'distance' => $ride->distance(),
            'status' => $ride->status(),
//            'from_lat' => $ride->fromLatitude(),
//            'from_long' => $ride->fromLongitude(),
//            'to_lat' => $ride->toLatitude(),
//            'to_long' => $ride->toLongitude(),
//            'last_lat' => $ride->lastLatitude(),
//            'last_long' => $ride->lastLongitude(),
            'updated_at' => $ride->updatedAt(),
        ], terms: "ride_id = :ride_id", params: "ride_id={$ride->rideId()}");
    }

    public function getAcceptRidesByDriverId(string $driverId): array
    {
        $rides = $this->model->find(
            terms: "driver_id = :driver_id and status = :status",
            params: "driver_id={$driverId}&status=accepted"
        )->fetchAll();
        if (!$rides) {
            return [];
        }
        return $this->outputRides(rides: $rides);
    }
}
