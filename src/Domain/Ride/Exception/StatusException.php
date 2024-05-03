<?php

declare(strict_types=1);

namespace Domain\Ride\Exception;

class StatusException extends \InvalidArgumentException
{
    public static function invalid(): StatusException
    {
        return new self(message: 'Invalid status.', code: 422);
    }

    public static function requested(): StatusException
    {
        return new self(message: 'Ride cannot requested.', code: 422);
    }

    public static function accepted(): StatusException
    {
        return new self(message: 'Ride cannot accepted.', code: 422);
    }

    public static function started(): StatusException
    {
        return new self(message: 'Ride cannot started.', code: 422);
    }

    public static function updatePosition(): StatusException
    {
        return new self(message: 'Ride cannot update position.', code: 422);
    }

    public static function finish(): StatusException
    {
        return new self(message: 'Ride cannot finish.', code: 422);
    }
}
