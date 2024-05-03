<?php

declare(strict_types=1);

namespace Integration\Application\Ride\Command;

use Application\Auth\Command\SignUp;
use Application\Auth\Command\SignUpHandler;
use Application\Ride\Command\AcceptRide;
use Application\Ride\Command\AcceptRideHandler;
use Application\Ride\Command\FinishRide;
use Application\Ride\Command\FinishRideHandler;
use Application\Ride\Command\PaymentHandler;
use Application\Ride\Command\RequestRide;
use Application\Ride\Command\RequestRideHandler;
use Application\Ride\Command\StartRide;
use Application\Ride\Command\StartRideHandler;
use Application\Ride\Command\UpdatePosition;
use Application\Ride\Command\UpdatePositionHandler;
use Application\Ride\Query\GetPositionsQuery;
use Application\Ride\Query\GetRideQuery;
use Domain\Ride\Event\Handler\PaymentRideFinished;
use Domain\Ride\Event\RideCompletedEvent;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Mediator\Mediator;
use Infrastructure\Persistence\Account\Model\Account;
use Infrastructure\Persistence\Account\Repository\AccountRepository;
use Infrastructure\Persistence\Ride\Model\Position;
use Infrastructure\Persistence\Ride\Model\Ride;
use Infrastructure\Persistence\Ride\Repository\PositionRepository;
use Infrastructure\Persistence\Ride\Repository\RideRepository;
use Tests\DatabaseTestCase;
use Tests\Stubs\MailerGatewayStub;
use Tests\Stubs\RabbitMQAdapterStub;

class UpdatePositionHandlerTest extends DatabaseTestCase
{
    public StartRideHandler $startRideHandler;
    public SignUpHandler $signUpHandler;
    public RequestRideHandler $requestRideHandler;
    public GetRideQuery $getRideQuery;
    public AcceptRideHandler $acceptRideHandler;
    public FinishRideHandler $finishRideHandler;
    public UpdatePositionHandler $updatePositionHandler;
    public GetPositionsQuery $getPositionsQuery;

    protected function setUp(): void
    {
        parent::setUp();
        $mysql = new MySqlDbConnectionAdapter();
        $mysql->connectTesting();
        $rideRepository = new RideRepository(model: new Ride(databaseConnection: $mysql));
        $this->startRideHandler = new StartRideHandler(
            rideRepository: $rideRepository,
            queue: new RabbitMQAdapterStub()
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
        $mediator = new Mediator();
        $mediator->events()->register(
            eventName: RideCompletedEvent::class,
            eventHandler: new PaymentRideFinished(paymentHandler: new PaymentHandler())
        );
        $this->finishRideHandler = new FinishRideHandler(
            rideRepository: $rideRepository,
            queue: new RabbitMQAdapterStub(),
            mediator: $mediator
        );
        $positionRepository = new PositionRepository(model: new Position(databaseConnection: $mysql));
        $this->updatePositionHandler = new UpdatePositionHandler(
            rideRepository: $rideRepository,
            positionRepository: $positionRepository
        );
        $this->getPositionsQuery = new GetPositionsQuery(databaseConnection: $mysql);
    }

    public function test_should_be_update_position()
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
        $inputPosition = new UpdatePosition(rideId: $outputRequestRide->rideId, latitude: -23.5396509401, longitude: -46.4847533443);
        ($this->updatePositionHandler)(command: $inputPosition);
        $outputGetPositions = ($this->getPositionsQuery)(rideId: $outputRequestRide->rideId);
        $this->assertNotNull($outputGetPositions['position_id']);
        $this->assertNotNull($outputGetPositions['created_at']);
        $this->assertNotNull($outputGetPositions['updated_at']);
        $this->assertEquals($outputRequestRide->rideId, $outputGetPositions['ride_id']);
        $this->assertEquals(-23.5396509401, $outputGetPositions['latitude']);
        $this->assertEquals(-46.4847533443, $outputGetPositions['longitude']);
    }
}
