<?php

declare(strict_types=1);

namespace Domain\Ride\Entity;

use Domain\Ride\Enum\Status;
use Domain\Ride\Event\RideCompletedEvent;
use Domain\Ride\Event\RideStartedEvent;
use Domain\Ride\Exception\StatusException;
use Domain\Ride\Factory\FareCalculatorFactory;
use Domain\Ride\Service\DistanceCalculator;
use Domain\Ride\ValueObject\Coordinate;
use Domain\Shared\Entity\AggregateRoot;
use Domain\Shared\ValueObject\Uuid;

class Ride extends AggregateRoot
{
    private readonly Uuid $rideId;
    private readonly Coordinate $from;
    private readonly Coordinate $to;
    private Coordinate $lastPosition;

    private Status $status;

    private \DateTimeInterface $createdAt;
    private \DateTimeInterface $updatedAt;

    private function __construct(
        string $rideId,
        private readonly string $passengerId,
        float $fromLatitude,
        float $fromLongitude,
        float $toLatitude,
        float $toLongitude,
        float $lastLatitude,
        float $lastLongitude,
        string $status,
        private float $distance,
        private float $fare,
        string $createdAt,
        string $updatedAt,
        private ?string $driverId = null,
    ) {
        $this->rideId = new Uuid(value: $rideId);
        $this->from = new Coordinate(latitude: $fromLatitude, longitude: $fromLongitude);
        $this->to = new Coordinate(latitude: $toLatitude, longitude: $toLongitude);
        $this->lastPosition = new Coordinate(latitude: $lastLatitude, longitude: $lastLongitude);
        $this->status = Status::from(value: $status);
        $this->createdAt = new \DateTime(datetime: $createdAt);
        $this->updatedAt = new \DateTime(datetime: $updatedAt);
    }

    public static function create(
        string $passengerId,
        float $fromLatitude,
        float $fromLongitude,
        float $toLatitude,
        float $toLongitude,
    ): Ride {
        $createdAt = (new \DateTime())->format('Y-m-d H:i:s');
        return new Ride(
            rideId: Uuid::random()->value(),
            passengerId: $passengerId,
            fromLatitude: $fromLatitude,
            fromLongitude: $fromLongitude,
            toLatitude: $toLatitude,
            toLongitude: $toLongitude,
            lastLatitude: $fromLatitude,
            lastLongitude: $fromLongitude,
            status: "requested",
            distance: 0.0,
            fare: 0.0,
            createdAt: $createdAt,
            updatedAt: $createdAt
        );
    }

    public static function restore(
        string $rideId,
        string $passengerId,
        float $fromLatitude,
        float $fromLongitude,
        float $toLatitude,
        float $toLongitude,
        float $lastLatitude,
        float $lastLongitude,
        string $status,
        float $distance,
        float $fare,
        string $createdAt,
        string $updatedAt,
        ?string $driverId = null,
    ): Ride {
        return new Ride(
            rideId: $rideId,
            passengerId: $passengerId,
            fromLatitude: $fromLatitude,
            fromLongitude: $fromLongitude,
            toLatitude: $toLatitude,
            toLongitude: $toLongitude,
            lastLatitude: $lastLatitude,
            lastLongitude: $lastLongitude,
            status: $status,
            distance: $distance,
            fare: $fare,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            driverId: $driverId
        );
    }

    public function rideId(): string
    {
        return $this->rideId->value();
    }

    public function passengerId(): string
    {
        return $this->passengerId;
    }

    public function fromLatitude(): float
    {
        return $this->from->latitude();
    }

    public function fromLongitude(): float
    {
        return $this->from->longitude();
    }

    public function toLatitude(): float
    {
        return $this->to->latitude();
    }

    public function toLongitude(): float
    {
        return $this->to->longitude();
    }

    public function lastLatitude(): float
    {
        return $this->lastPosition->latitude();
    }

    public function lastLongitude(): float
    {
        return $this->lastPosition->longitude();
    }

    public function status(): string
    {
        return $this->status->value;
    }

    public function distance(): float
    {
        return $this->distance;
    }

    public function fare(): float
    {
        return $this->fare;
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }

    public function updatedAt(): string
    {
        return $this->updatedAt->format('Y-m-d H:i:s');
    }

    public function driverId(): ?string
    {
        return $this->driverId;
    }

    public function accept(string $driverId): void
    {
        if ($this->status->value !== Status::REQUESTED->value) {
            throw StatusException::accepted();
        }
        $this->status = Status::ACCEPTED;
        $this->driverId = $driverId;
        $this->updatedAt = new \DateTime();
    }

    public function start(): void
    {
        if ($this->status->value !== Status::ACCEPTED->value) {
            throw StatusException::started();
        }
        $this->status = Status::IN_PROGRESS;
        $event = new RideStartedEvent(ride: $this);
        $this->events()->notify(event: $event);
    }

    public function updatePosition(float $latitude, float $longitude): void
    {
        if ($this->status->value !== Status::IN_PROGRESS->value) {
            throw StatusException::updatePosition();
        }
        $newLastPosition = new Coordinate(latitude: $latitude, longitude: $longitude);
        $this->distance += DistanceCalculator::calculate(from: $this->lastPosition, to: $newLastPosition);
        $this->lastPosition = $newLastPosition;
    }

    /**
     * @throws \Exception
     */
    public function finish(): void
    {
        if ($this->status->value !== Status::IN_PROGRESS->value) {
            throw StatusException::finish();
        }
        $this->status = Status::COMPLETED;
        $this->fare = FareCalculatorFactory::create(date: $this->createdAt)->calculate($this->distance);
        $event = new RideCompletedEvent(ride: $this, creditCardToken: '123456');
        $this->events()->notify(event: $event);
    }
}
