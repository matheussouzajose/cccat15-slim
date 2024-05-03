<?php

declare(strict_types=1);

namespace Domain\Account\Exception;

class NameException extends \InvalidArgumentException
{
    public static function invalid(string $name): NameException
    {
        $message = sprintf('The name %s is invalid.', $name);
        return new self(message: $message, code: 422);
    }
}
