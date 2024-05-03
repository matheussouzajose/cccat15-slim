<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Ride\Model;

use Infrastructure\Persistence\Model;

class RideProjection extends Model
{
    protected string $table = 'ride_projection';

    public function getByRideId(string $rideId): array
    {
        $stmt = $this->databaseConnection->getConnect()->prepare(
            query: "select r.ride_id,
				    r.ride_id,
                    r.status,
                    r.created_at,
                    r.fare,
                    r.distance,
                    p.name as passenger_name,
                    p.email as passenger_email,
                    d.name as driver_name,
                    d.email as driver_email
                from ride r
                join account p on r.passenger_id = p.account_id
                left join account d on r.driver_id = d.account_id
                where ride_id = :rideId"
        );
        $stmt->execute(['rideId' => $rideId]);
        $result = $stmt->fetch();
        return !$result ? [] : (array)$result;
    }
}
