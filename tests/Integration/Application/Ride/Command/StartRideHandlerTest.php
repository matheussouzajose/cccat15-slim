<?php

declare(strict_types=1);

namespace Integration\Application\Ride\Command;

use Application\Auth\Command\SignUp;
use Application\Auth\Command\SignUpHandler;
use Application\Ride\Command\AcceptRide;
use Application\Ride\Command\AcceptRideHandler;
use Application\Ride\Command\RequestRide;
use Application\Ride\Command\RequestRideHandler;
use Application\Ride\Command\StartRide;
use Application\Ride\Command\StartRideHandler;
use Application\Ride\Command\UpdateRideProjection;
use Application\Ride\Command\UpdateRideProjectionHandler;
use Application\Ride\Query\GetRideProjectionQuery;
use Application\Ride\Query\GetRideQuery;
use Domain\Ride\Exception\RideException;
use Domain\Ride\Exception\StatusException;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Account\Model\Account;
use Infrastructure\Persistence\Account\Repository\AccountRepository;
use Infrastructure\Persistence\Ride\Model\Ride;
use Infrastructure\Persistence\Ride\Model\RideProjection;
use Infrastructure\Persistence\Ride\Repository\RideProjectionRepository;
use Infrastructure\Persistence\Ride\Repository\RideRepository;
use Infrastructure\Queue\RabbitMQAdapter;
use Tests\DatabaseTestCase;
use Tests\Stubs\MailerGatewayStub;
use Tests\Stubs\RabbitMQAdapterStub;

class StartRideHandlerTest extends DatabaseTestCase
{
    public StartRideHandler $startRideHandler;
    public SignUpHandler $signUpHandler;
    public RequestRideHandler $requestRideHandler;
    public GetRideQuery $getRideQuery;
    public AcceptRideHandler $acceptRideHandler;
    public UpdateRideProjectionHandler $updateRideProjectionHandler;
    public GetRideProjectionQuery $getRideProjectionQuery;

    protected function setUp(): void
    {
        parent::setUp();
        $mysql = new MySqlDbConnectionAdapter();
        $mysql->connectTesting();
        $rideRepository = new RideRepository(model: new Ride(databaseConnection: $mysql));
        $this->startRideHandler = new StartRideHandler(
            rideRepository: $rideRepository,
            queue: new RabbitMQAdapter()
        );
        $accountRepository = new AccountRepository(model: new Account(databaseConnection: $mysql));
        $this->signUpHandler = new SignUpHandler(
            accountRepository: $accountRepository,
            mailerGateway: new MailerGatewayStub()
        );
        $this->requestRideHandler = new RequestRideHandler(
            rideRepository: $rideRepository,
            accountRepository: $accountRepository
        );
        $this->getRideQuery = new GetRideQuery(databaseConnection: $mysql);
        $this->acceptRideHandler = new AcceptRideHandler(
            rideRepository: $rideRepository,
            accountRepository: $accountRepository
        );
        $rideProjectionRepository = new RideProjectionRepository(model: new RideProjection(databaseConnection: $mysql));
        $this->updateRideProjectionHandler = new UpdateRideProjectionHandler(
            rideProjectionRepository: $rideProjectionRepository
        );
        $this->getRideProjectionQuery = new GetRideProjectionQuery(databaseConnection: $mysql);
    }

//    public function test_throws_an_exception_when_ride_not_exist()
//    {
//        $expectedId = 'invalid';
//        $this->expectExceptionObject(RideException::notExist(id: $expectedId));
//        $inputStartRide = new StartRide(rideId: $expectedId);
//        ($this->startRideHandler)(command: $inputStartRide);
//    }
//
//    public function test_throws_error_status_is_not_accepted()
//    {
//        $inputSignUpPassenger = new SignUp(
//            name: 'John Doe',
//            email: 'jonh.doe@mail.com',
//            cpf: '97456321558',
//            isPassenger: true
//        );
//        $outputSignUpPassenger = ($this->signUpHandler)(command: $inputSignUpPassenger);
//        $inputRequestRide = new RequestRide(
//            passengerId: $outputSignUpPassenger->accountId,
//            fromLatitude: -27.584905257812,
//            fromLongitude: -48.54502219545,
//            toLatitude: -27.496887588311,
//            toLongitude: -48.522234807858
//        );
//        $outputRequestRide = ($this->requestRideHandler)(command: $inputRequestRide);
//        $this->expectExceptionObject(StatusException::started());
//        $inputStartRide = new StartRide(rideId: $outputRequestRide->rideId);
//        ($this->startRideHandler)(command: $inputStartRide);
//    }

    public function test_should_be_start_ride()
    {
        $inputSignUpPassenger = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        $outputSignUpPassenger = ($this->signUpHandler)(command: $inputSignUpPassenger);
        $inputSignUpDriver = new SignUp(
            name: 'John B',
            email: 'jonh.b@mail.com',
            cpf: '97456321558',
            isPassenger: false,
            isDriver: true,
            carPlate: 'AMD1234'
        );
        $outputSignUpDriver = ($this->signUpHandler)(command: $inputSignUpDriver);
        $inputRequestRide = new RequestRide(
            passengerId: $outputSignUpPassenger->accountId,
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858
        );
        $outputRequestRide = ($this->requestRideHandler)(command: $inputRequestRide);
        $inputAcceptRide = new AcceptRide(rideId: $outputRequestRide->rideId, driverId: $outputSignUpDriver->accountId);
        ($this->acceptRideHandler)(command: $inputAcceptRide);
        $inputStartRide = new StartRide(rideId: $outputRequestRide->rideId);
        ($this->startRideHandler)(command: $inputStartRide);
        $inputUpdateRideProjection = new UpdateRideProjection(rideId: $outputRequestRide->rideId);
        ($this->updateRideProjectionHandler)(command: $inputUpdateRideProjection);
        $outputGetRideProjectionQuery = ($this->getRideProjectionQuery)(rideId: $outputRequestRide->rideId);
        $this->assertEquals($outputRequestRide->rideId, $outputGetRideProjectionQuery['ride_id']);
        $this->assertEquals('in_progress', $outputGetRideProjectionQuery['status']);
        $this->assertEquals(0.000000000000000, $outputGetRideProjectionQuery['fare']);
        $this->assertEquals(0.000000000000000, $outputGetRideProjectionQuery['distance']);
        $this->assertEquals('John Doe', $outputGetRideProjectionQuery['passenger_name']);
        $this->assertEquals('jonh.doe@mail.com', $outputGetRideProjectionQuery['passenger_email']);
        $this->assertEquals('John B', $outputGetRideProjectionQuery['driver_name']);
        $this->assertEquals('jonh.b@mail.com', $outputGetRideProjectionQuery['driver_email']);
        $this->assertNotEmpty($outputGetRideProjectionQuery['created_at']);
        $this->assertNotEmpty($outputGetRideProjectionQuery['updated_at']);
    }
}
