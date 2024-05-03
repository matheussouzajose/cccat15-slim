<?php

declare(strict_types=1);

namespace Domain\Ride\Enum;

enum Status: string
{
    case REQUESTED = 'requested';
    case ACCEPTED = 'accepted';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
