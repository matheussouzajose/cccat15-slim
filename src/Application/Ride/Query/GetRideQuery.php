<?php

declare(strict_types=1);

namespace Application\Ride\Query;

use Domain\Ride\Exception\RideException;
use Infrastructure\Database\Contracts\DbConnectionInterface;

class GetRideQuery
{
    public function __construct(private readonly DbConnectionInterface $databaseConnection)
    {
    }

    public function __invoke(string $rideId): array
    {
        $stmt = $this->databaseConnection->getConnect()->prepare(
            query: "select r.ride_id,
				    r.from_lat,
				    r.from_long,
				    r.to_lat,
				    r.to_long,
				    r.last_lat,
				    r.last_long,
				    r.status,
				    r.fare,
				    r.distance,
				    r.created_at,
				    r.updated_at,
				    p.account_id as passenger_id,
				    p.name as passenger_name,
                    p.email as passenger_email,
                    d.account_id as driver_id,
                    d.name as driver_name,
				    d.email as driver_email
                from ride r
                join account p on r.passenger_id = p.account_id
                left join account d on r.driver_id = d.account_id
                where ride_id = :rideId"
        );
        $stmt->execute(['rideId' => $rideId]);
        if (!$stmt->rowCount()) {
            throw RideException::notExist(id: $rideId);
        }
        $result = $stmt->fetchAll(mode: \PDO::FETCH_CLASS, args: static::class);
        return $this->output(result: $result);
    }

    private function output(array $result): array
    {
        return array_map(function ($item) {
            return [
                'rideId' => $item->ride_id,
                'passengerId' => $item->passenger_id,
                'driverId' => $item->driver_id,
                'fromLatitude' => $item->from_lat,
                'fromLongitude' => $item->from_long,
                'toLatitude' => $item->to_lat,
                'toLongitude' => $item->to_long,
                'lastLatitude' => $item->last_lat,
                'lastLongitude' => $item->last_long,
                'status' => $item->status,
                'distance' => $item->distance,
                'fare' => $item->fare,
                'createdAt' => $item->created_at,
                'updatedAt' => $item->updated_at,
                'passengerName' => $item->passenger_name,
                'driverName' => $item->driver_name,
            ];
        }, $result);
    }
}
