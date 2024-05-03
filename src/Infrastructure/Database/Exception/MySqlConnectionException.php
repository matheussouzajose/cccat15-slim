<?php

declare(strict_types=1);

namespace Infrastructure\Database\Exception;

class MySqlConnectionException extends \InvalidArgumentException
{
    public static function error(string $error): MySqlConnectionException
    {
        $message = sprintf('Database connection error: %s', $error);
        return new self(message: $message, code: 500);
    }
}
