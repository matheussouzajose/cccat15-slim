<?php

declare(strict_types=1);

namespace Domain\Ride\Exception;

class CoordinateException extends \InvalidArgumentException
{
    public static function invalidLatitude(): CoordinateException
    {
        return new self(message: 'Invalid latitude.', code: 422);
    }

    public static function invalidLongitude(): CoordinateException
    {
        return new self(message: 'Invalid longitude.', code: 422);
    }
}
