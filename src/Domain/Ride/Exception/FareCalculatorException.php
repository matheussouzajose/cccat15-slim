<?php

declare(strict_types=1);

namespace Domain\Ride\Exception;

class FareCalculatorException extends \InvalidArgumentException
{
    public static function error(): FareCalculatorException
    {
        return new self(message: 'An error occurred when calculate fare.', code: 422);
    }
}
