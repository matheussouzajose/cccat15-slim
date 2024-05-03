<?php

declare(strict_types=1);

namespace Domain\Ride\Entity;

use Domain\Ride\ValueObject\Coordinate;
use Domain\Shared\ValueObject\Uuid;

class Position
{
    private readonly Uuid $positionId;
    private readonly Coordinate $coordinate;
    private \DateTimeInterface $createdAt;
    private \DateTimeInterface $updatedAt;

    /**
     * @throws \Exception
     */
    private function __construct(
        string $positionId,
        protected string $rideId,
        float $latitude,
        float $longitude,
        string $createdAt,
        string $updatedAt,
    ) {
        $this->positionId = new Uuid(value: $positionId);
        $this->coordinate = new Coordinate(latitude: $latitude, longitude: $longitude);
        $this->createdAt = new \DateTime(datetime: $createdAt);
        $this->updatedAt = new \DateTime(datetime: $updatedAt);
    }

    /**
     * @throws \Exception
     */
    public static function create(string $rideId, float $latitude, float $longitude): Position
    {
        $createdAt = (new \DateTime())->format('Y-m-d H:i:s');
        return new Position(
            positionId: Uuid::random()->value(),
            rideId: $rideId,
            latitude: $latitude,
            longitude: $longitude,
            createdAt: $createdAt,
            updatedAt: $createdAt
        );
    }

    /**
     * @throws \Exception
     */
    public static function restore(
        string $positionId,
        string $rideId,
        float $latitude,
        float $longitude,
        string $createdAt,
        string $updatedAt
    ): Position {
        return new Position(
            positionId: $positionId,
            rideId: $rideId,
            latitude: $latitude,
            longitude: $longitude,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function positionId(): string
    {
        return $this->positionId->value();
    }

    public function rideId(): string
    {
        return $this->rideId;
    }

    public function latitude(): float
    {
        return $this->coordinate->latitude();
    }

    public function longitude(): float
    {
        return $this->coordinate->longitude();
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }

    public function updatedAt(): string
    {
        return $this->updatedAt->format('Y-m-d H:i:s');
    }
}
