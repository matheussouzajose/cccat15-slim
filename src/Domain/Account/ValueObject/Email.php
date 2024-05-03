<?php

declare(strict_types=1);

namespace Domain\Account\ValueObject;

use Domain\Account\Exception\EmailException;
use Respect\Validation\Validator as v;

class Email
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    private function ensureIsValid(string $email): void
    {
        if (!v::email()->validate($email)) {
            throw EmailException::invalid(email: $email);
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
