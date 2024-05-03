<?php

declare(strict_types=1);

namespace Domain\Account\ValueObject;

use Domain\Account\Exception\CpfException;
use Respect\Validation\Validator as v;

class Cpf
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    private function ensureIsValid(string $cpf): void
    {
        if (!v::cpf()->validate($cpf)) {
            throw CpfException::invalid(cpf: $cpf);
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
