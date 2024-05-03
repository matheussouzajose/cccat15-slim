<?php

declare(strict_types=1);

namespace Domain\Ride\Contracts;

use Domain\Ride\Entity\Position;

interface PositionRepositoryInterface
{
    public function create(Position $position);
}
