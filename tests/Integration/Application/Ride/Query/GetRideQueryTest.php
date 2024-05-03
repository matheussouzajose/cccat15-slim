<?php

declare(strict_types=1);

namespace Integration\Application\Ride\Query;

use Application\Auth\Command\SignUp;
use Application\Auth\Command\SignUpHandler;
use Application\Ride\Command\RequestRide;
use Application\Ride\Command\RequestRideHandler;
use Application\Ride\Query\GetRideQuery;
use Domain\Ride\Exception\RideException;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Account\Model\Account;
use Infrastructure\Persistence\Account\Repository\AccountRepository;
use Infrastructure\Persistence\Ride\Model\Ride;
use Infrastructure\Persistence\Ride\Repository\RideRepository;
use Tests\DatabaseTestCase;
use Tests\Stubs\MailerGatewayStub;

class GetRideQueryTest extends DatabaseTestCase
{
    public GetRideQuery $getRideQuery;
    public RequestRideHandler $requestRideHandler;
    public SignUpHandler $signUpHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $mysql = new MySqlDbConnectionAdapter();
        $mysql->connectTesting();
        $accountRepository = new AccountRepository(model: new Account(databaseConnection: $mysql));
        $this->getRideQuery = new GetRideQuery(databaseConnection: $mysql);
        $rideRepository = new RideRepository(model: new Ride(databaseConnection: $mysql));
        $this->requestRideHandler = new RequestRideHandler(
            rideRepository: $rideRepository,
            accountRepository: $accountRepository
        );
        $this->signUpHandler = new SignUpHandler(
            accountRepository: $accountRepository,
            mailerGateway: new MailerGatewayStub()
        );
    }

    public function test_throw_an_exception_when_ride_no_exist()
    {
        $this->expectExceptionObject(RideException::notExist(id: 'invalid'));
        ($this->getRideQuery)(rideId: 'invalid');
    }

    public function test_should_be_return_ride()
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
        $output = ($this->getRideQuery)(rideId: $outputRequestRide->rideId);
        $this->assertEquals($outputRequestRide->rideId, $output[0]['rideId']);
        $this->assertEquals($outputSignUp->accountId, $output[0]['passengerId']);
        $this->assertNull($output[0]['driverId']);
        $this->assertEquals(-27.584905257812, $output[0]['fromLatitude']);
        $this->assertEquals(-48.54502219545, $output[0]['fromLongitude']);
        $this->assertEquals(-27.496887588311, $output[0]['toLatitude']);
        $this->assertEquals(-48.522234807858, $output[0]['toLongitude']);
        $this->assertEquals(-27.584905257812, $output[0]['lastLatitude']);
        $this->assertEquals(-48.54502219545, $output[0]['lastLongitude']);
        $this->assertEquals('requested', $output[0]['status']);
        $this->assertEquals(0.0, $output[0]['distance']);
        $this->assertEquals(0.0, $output[0]['fare']);
        $this->assertNotNull($output[0]['createdAt']);
        $this->assertNotNull($output[0]['updatedAt']);
        $this->assertEquals('John Doe', $output[0]['passengerName']);
        $this->assertNull($output[0]['driverName']);
    }
}
