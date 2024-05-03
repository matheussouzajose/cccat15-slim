<?php

declare(strict_types=1);

namespace Domain\Account\Exception;

class EmailException extends \InvalidArgumentException
{
    public static function invalid(string $email): EmailException
    {
        $message = sprintf('The email %s is invalid.', $email);
        return new self(message: $message, code: 422);
    }

    public static function alreadyExist(string $email): EmailException
    {
        $message = sprintf('Email %s already exists', $email);
        return new self(message: $message, code: 422);
    }
}
