<?php

declare(strict_types=1);

namespace Domain\Account\Exception;

class AccountException extends \InvalidArgumentException
{
    public static function invalidEmail(string $email): AccountException
    {
        $message = sprintf('The email %s is invalid.', $email);
        return new self(message: $message, code: 422);
    }

    public static function notFound(string $id): AccountException
    {
        $message = sprintf('Account %s does not exist', $id);
        return new self(message: $message, code: 404);
    }

    public static function notPassenger(): AccountException
    {
        return new self(message: 'Account is not from a passenger', code: 422);
    }

    public static function notDriver(): AccountException
    {
        return new self(message: 'Account is not from a driver', code: 422);
    }

    public static function alreadyExist(): AccountException
    {
        return new self(message: 'Account already exists', code: 422);
    }
}
