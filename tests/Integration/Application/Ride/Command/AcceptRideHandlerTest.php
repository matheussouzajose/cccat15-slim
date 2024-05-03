<?php

declare(strict_types=1);

namespace Integration\Application\Ride\Command;

use Application\Auth\Command\SignUp;
use Application\Auth\Command\SignUpHandler;
use Application\Ride\Command\AcceptRide;
use Application\Ride\Command\AcceptRideHandler;
use Application\Ride\Command\RequestRide;
use Application\Ride\Command\RequestRideHandler;
use Application\Ride\Query\GetRideQuery;
use Domain\Account\Exception\AccountException;
use Domain\Ride\Exception\RideException;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Persistence\Account\Model\Account;
use Infrastructure\Persistence\Account\Repository\AccountRepository;
use Infrastructure\Persistence\Ride\Model\Ride;
use Infrastructure\Persistence\Ride\Repository\RideRepository;
use Tests\DatabaseTestCase;
use Tests\Stubs\MailerGatewayStub;

class AcceptRideHandlerTest extends DatabaseTestCase
{
    public AcceptRideHandler $acceptRideHandler;
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
        $ride = new Ride(databaseConnection: $mysql);
        $rideRepository = new RideRepository(model: $ride);
        $this->acceptRideHandler = new AcceptRideHandler(
            rideRepository: $rideRepository,
            accountRepository: $accountRepository
        );
        $this->signUpHandler = new SignUpHandler(
            accountRepository: $accountRepository,
            mailerGateway: new MailerGatewayStub()
        );
        $this->requestRideHandler = new RequestRideHandler(
            rideRepository: $rideRepository,
            accountRepository: $accountRepository
        );
        $this->getRideQuery = new GetRideQuery(databaseConnection: $mysql);
    }

    public function test_throw_an_exception_when_ride_not_found()
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
        $this->expectExceptionObject(RideException::notExist(id: 'invalid'));
        $command = new AcceptRide(rideId: 'invalid', driverId: $outputSignUp->accountId);
        ($this->acceptRideHandler)(command: $command);
    }

    public function test_throw_an_exception_when_driver_account_not_found()
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
        $this->expectExceptionObject(AccountException::notFound(id: 'invalid'));
        $command = new AcceptRide(rideId: $outputRequestRide->rideId, driverId: 'invalid');
        ($this->acceptRideHandler)(command: $command);
    }

    public function test_throw_an_exception_when_account_is_not_driver()
    {
        $inputSignUpPassenger = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUpPassenger);
        $inputRequestRide = new RequestRide(
            passengerId: $outputSignUp->accountId,
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858
        );
        $inputSignUpDriver = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe2@mail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUpDriver);
        $outputRequestRide = ($this->requestRideHandler)(command: $inputRequestRide);
        $this->expectExceptionObject(AccountException::notDriver());
        $command = new AcceptRide(rideId: $outputRequestRide->rideId, driverId: $outputSignUp->accountId);
        ($this->acceptRideHandler)(command: $command);
    }

    public function test_throw_an_exception_when_driver_has_active_ride()
    {
        $inputSignUpPassenger = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUpPassenger);
        $inputRequestRide = new RequestRide(
            passengerId: $outputSignUp->accountId,
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858
        );
        $inputSignUpDriver = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe2@mail.com',
            cpf: '97456321558',
            isPassenger: false,
            isDriver: true,
            carPlate: 'AMD1234'
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUpDriver);
        $outputRequestRide = ($this->requestRideHandler)(command: $inputRequestRide);
        $command = new AcceptRide(rideId: $outputRequestRide->rideId, driverId: $outputSignUp->accountId);
        ($this->acceptRideHandler)(command: $command);
        $this->expectExceptionObject(RideException::driverHasAnAcceptRide());
        ($this->acceptRideHandler)(command: $command);
    }

    public function test_should_be_accept_ride()
    {
        $inputSignUpPassenger = new SignUp(
            name: 'John Doe',
            email: 'jonh.doe@mail.com',
            cpf: '97456321558',
            isPassenger: true
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUpPassenger);
        $inputRequestRide = new RequestRide(
            passengerId: $outputSignUp->accountId,
            fromLatitude: -27.584905257812,
            fromLongitude: -48.54502219545,
            toLatitude: -27.496887588311,
            toLongitude: -48.522234807858
        );
        $inputSignUpDriver = new SignUp(
            name: 'John B',
            email: 'jonh.b@mail.com',
            cpf: '97456321558',
            isPassenger: false,
            isDriver: true,
            carPlate: 'AMD1234'
        );
        $outputSignUp = ($this->signUpHandler)(command: $inputSignUpDriver);
        $outputRequestRide = ($this->requestRideHandler)(command: $inputRequestRide);
        $command = new AcceptRide(rideId: $outputRequestRide->rideId, driverId: $outputSignUp->accountId);
        ($this->acceptRideHandler)(command: $command);
        $outputGetRide = ($this->getRideQuery)($outputRequestRide->rideId);
        $this->assertEquals('accepted', $outputGetRide[0]['status']);
        $this->assertEquals('John B', $outputGetRide[0]['driverName']);
    }
}
