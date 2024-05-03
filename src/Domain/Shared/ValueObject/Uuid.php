<?php

declare(strict_types=1);

namespace Domain\Shared\ValueObject;

use Domain\Shared\Exception\UuidException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    private function ensureIsValid(string $id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw UuidException::invalid(uuid: $id);
        }
    }

    public static function random(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }
}
