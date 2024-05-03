<?php

declare(strict_types=1);

namespace Domain\Account\Exception;

class CpfException extends \InvalidArgumentException
{
    public static function invalid(string $cpf): CpfException
    {
        $message = sprintf('The cpf %s is invalid.', $cpf);
        return new self(message: $message, code: 422);
    }
}
