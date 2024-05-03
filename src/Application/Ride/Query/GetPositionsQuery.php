<?php

declare(strict_types=1);

namespace Application\Ride\Query;

use Infrastructure\Database\Contracts\DbConnectionInterface;

class GetPositionsQuery
{
    public function __construct(private readonly DbConnectionInterface $databaseConnection)
    {
    }

    public function __invoke(string $rideId): array
    {
        $stmt = $this->databaseConnection->getConnect()->prepare(
            query: "select * from position where ride_id = :rideId"
        );
        $stmt->execute(['rideId' => $rideId]);
        return (array)$stmt->fetch();
    }
}
