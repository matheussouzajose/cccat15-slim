<?php

declare(strict_types=1);

namespace Domain\Shared\Exception;

class UuidException extends \InvalidArgumentException
{
    public static function invalid(string $uuid): UuidException
    {
        $message = sprintf('The uuid %s is invalid.', $uuid);
        return new self(message: $message, code: 422);
    }
}
