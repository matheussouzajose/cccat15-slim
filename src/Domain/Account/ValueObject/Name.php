<?php

declare(strict_types=1);

namespace Domain\Account\ValueObject;

use Domain\Account\Exception\NameException;

class Name
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    private function ensureIsValid(string $name): void
    {
        if ($this->isInvalidName(name: $name)) {
            throw NameException::invalid(name: $name);
        }
    }

    private function isInvalidName(string $name): bool
    {
        return !preg_match('/^[a-zA-Z]+(?:\s[a-zA-Z]+)+$/', $name);
    }

    public function value(): string
    {
        return $this->value;
    }
}
