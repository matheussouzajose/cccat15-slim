<?php

declare(strict_types=1);

namespace Integration\Application\Ride\Command;

use Application\Auth\Command\SignUp;
use Application\Auth\Command\SignUpHandler;
use Application\Ride\Command\RequestRide;
use Application\Ride\Command\RequestRideHandler;
use Application\Ride\Query\GetRideQuery;
use Domain\Account\Exception\AccountException;
use Domain\Ride\Exception\CoordinateException;
use Domain\Ride\Exception\RideException;
use Domain\Shared\ValueObject\Uuid;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Account\Model\Account;
use Infrastructure\Persistence\Account\Repository\AccountRepository;
use Infrastructure\Persistence\Ride\Model\Ride;
use Infrastructure\Persistence\Ride\Repository\RideRepository;
use Tests\DatabaseTestCase;
use Tests\Stubs\MailerGatewayStub;

class RequestRideHandlerTest extends DatabaseTestCase
{
    public SignUpHandler $signUpHandler;
    public RequestRideHandler $requestRideHandler;
    public GetRideQuery $getRideQuery;

    protected function setUp(): void
    {
        parent::setUp();
        $mysql = new MySqlDbConnectionAdapter();
        $mysql->connectTesting();
        $account = new Account(databaseConnection: $mysql);
        $accountRepository = new AccountRepository(model: $account);
        $this->signUpHandler = new SignUpHandler(
            accountRepository: $accountRepository,
            mailerGateway: new MailerGatewayStub()
        );
        $ride = new Ride(databaseConnection: $mysql);
        $rideRepository = new RideRepository(model: $ride);
        $this->requestRideHandler = new RequestRideHandler(
            rideRepository: $rideRepository,
            accountRepository: $accountRepository
        );
        $this->getRideQuery = new GetRideQuery(databaseConnection: $mysql);
    }

    public function test_throw_an_exception_when_given_invalid_data()
    {
        $this->expectExceptionObject(CoordinateException::invalidLatitude());
        $inputRequestRide = new RequestRide(
            passengerId: Uuid::random()->value(),
            fromLatitude: -100,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858
        );
        ($this->requestRideHandler)(command: $inputRequestRide);
    }

    public function test_throw_an_exception_when_account_no_exists()
    {
        $this->expectExceptionObject(AccountException::notFound(id: 'invalid'));
        $inputRequestRide = new RequestRide(
            passengerId: 'invalid',
            fromLatitude: -27.584905257808835,
            fromLongitude: -48.545022195325124,
            toLatitude: -27.496887588317275,
            toLongitude: -48.522234807851476
        );
        ($this->requestRideHandler)(command: $inputRequestRide);
    }

    public function test_throw_an_exception_when_account_is_not_from_passenger()
    {
        $inputSignUp = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: false,
            isDriver: true,
            carPlate: 'AMD1234'
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUp);
        $this->expectExceptionObject(AccountException::notPassenger());
        $inputRequestRide = new RequestRide(
            passengerId: $outputSignUp->accountId,
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858
        );
        ($this->requestRideHandler)(command: $inputRequestRide);
    }

    public function test_throw_an_exception_when_passenger_has_an_active_ride()
    {
        $inputSignUp = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUp);
        $inputRequestRide = new RequestRide(
            passengerId: $outputSignUp->accountId,
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858
        );
        ($this->requestRideHandler)(command: $inputRequestRide);
        $this->expectExceptionObject(RideException::passengerHasAnActiveRide());
        ($this->requestRideHandler)(command: $inputRequestRide);
    }

    public function test_should_be_request_a_ride()
    {
        $inputSignUp = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUp);
        $inputRequestRide = new RequestRide(
            passengerId: $outputSignUp->accountId,
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858
        );
        $outputRequestRide = ($this->requestRideHandler)(command: $inputRequestRide);
        $this->assertNotNull($outputRequestRide->rideId);
        $outputGetRideQuery = ($this->getRideQuery)(rideId: $outputRequestRide->rideId);
        $this->assertEquals($outputRequestRide->rideId, $outputGetRideQuery[0]['rideId']);
        $this->assertEquals($outputSignUp->accountId, $outputGetRideQuery[0]['passengerId']);
        $this->assertNull($outputGetRideQuery[0]['driverId']);
        $this->assertEquals(-27.584905257812, $outputGetRideQuery[0]['fromLatitude']);
        $this->assertEquals(-48.54502219545, $outputGetRideQuery[0]['fromLongitude']);
        $this->assertEquals(-27.496887588311, $outputGetRideQuery[0]['toLatitude']);
        $this->assertEquals(-48.522234807858, $outputGetRideQuery[0]['toLongitude']);
        $this->assertEquals(-27.584905257812, $outputGetRideQuery[0]['lastLatitude']);
        $this->assertEquals(-48.54502219545, $outputGetRideQuery[0]['lastLongitude']);
        $this->assertEquals('requested', $outputGetRideQuery[0]['status']);
        $this->assertEquals(0.0, $outputGetRideQuery[0]['distance']);
        $this->assertEquals(0.0, $outputGetRideQuery[0]['fare']);
        $this->assertNotNull($outputGetRideQuery[0]['createdAt']);
        $this->assertNotNull($outputGetRideQuery[0]['updatedAt']);
        $this->assertEquals('John Doe', $outputGetRideQuery[0]['passengerName']);
        $this->assertNull($outputGetRideQuery[0]['driverName']);
    }
}
