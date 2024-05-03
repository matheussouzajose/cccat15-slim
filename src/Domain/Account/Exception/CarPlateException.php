<?php

declare(strict_types=1);

namespace Domain\Account\Exception;

class CarPlateException extends \InvalidArgumentException
{
    public static function invalid(string $carPlate): CarPlateException
    {
        $message = sprintf('The car plate %s is invalid.', $carPlate);
        return new self(message: $message, code: 422);
    }
}
