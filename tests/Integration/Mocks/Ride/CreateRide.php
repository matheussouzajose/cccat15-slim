<?php

declare(strict_types=1);

namespace Integration\Mocks\Ride;

use Domain\Shared\ValueObject\Uuid;
use Infrastructure\Database\Contracts\DbConnectionInterface;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Ride\Model\Ride as Model;

class CreateRide
{
    public DbConnectionInterface $databaseConnection;
    public Model $model;

    public function __construct()
    {
        $this->databaseConnection = new MySqlDbConnectionAdapter();
        $this->databaseConnection->connectTesting();
        $this->model = new Model(databaseConnection: $this->databaseConnection);
    }

    public function create(?string $passengerId = null, ?string $driverId = null, ?string $status = null)
    {
        $id = Uuid::random()->value();
        $this->model->create([
            'ride_id' => $id,
            'passenger_id' => $passengerId ?? Uuid::random()->value(),
            'driver_id' => $driverId ?? Uuid::random()->value(),
            'fare' => 0,
            'distance' => 0,
            'status' => $status ?? 'requested',
            'from_lat' => -27.584905257808835,
            'from_long' => -48.545022195325124,
            'to_lat' => -27.496887588317275,
            'to_long' => -48.522234807851476,
            'last_lat' => -27.584905257808835,
            'last_long' => -48.545022195325124,
        ]);
        return $this->model->find(terms: "ride_id = :ride_id", params: "ride_id={$id}")->fetch();
    }
}
