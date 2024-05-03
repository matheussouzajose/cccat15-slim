<?php

declare(strict_types=1);

namespace Integration\Infrastructure\Persistence\Ride\Repository;

use Domain\Ride\Contracts\RideRepositoryInterface;
use Domain\Ride\Entity\Ride;
use Infrastructure\Database\Contracts\DbConnectionInterface;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Ride\Model\Ride as Model;
use Infrastructure\Persistence\Ride\Repository\RideRepository;
use Integration\Mocks\Ride\CreateRide;
use Tests\DatabaseTestCase;

class RideRepositoryTest extends DatabaseTestCase
{
    public DbConnectionInterface $databaseConnection;
    public Model $model;
    public RideRepositoryInterface $rideRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->databaseConnection = new MySqlDbConnectionAdapter();
        $this->databaseConnection->connectTesting();
        $this->model = new Model(databaseConnection: $this->databaseConnection);
        $this->rideRepository = new RideRepository(model: $this->model);
    }

    public function test_should_be_return_empty_when_passenger_has_not_active_ride()
    {
        $result = $this->rideRepository->getActiveRidesByPassengerId(passengerId: 'invalid');

        $this->assertCount(0, $result);
    }

    public function test_should_be_return_values_when_passenger_has_active_ride()
    {
        $ride = (new CreateRide())->create();
        $result = $this->rideRepository->getActiveRidesByPassengerId(passengerId: $ride->passenger_id);

        $this->assertCount(1, $result);
    }

    public function test_can_be_created()
    {
        $ride = Ride::create(passengerId: '', fromLatitude: 10, fromLongitude: 11, toLatitude: 12, toLongitude: 13);
        $result = $this->rideRepository->create(ride: $ride);

        $this->assertEquals($ride->rideId(), $result->rideId());
    }
}
