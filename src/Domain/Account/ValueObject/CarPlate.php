<?php

declare(strict_types=1);

namespace Domain\Account\ValueObject;

use Domain\Account\Exception\CarPlateException;

class CarPlate
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    private function ensureIsValid(string $carPlate): void
    {
        if ($this->isInvalidCarPlate(carPlate: $carPlate)) {
            throw CarPlateException::invalid(carPlate: $carPlate);
        }
    }

    private function isInvalidCarPlate(string $carPlate): bool
    {
        return !preg_match('/[A-Z]{3}[0-9]{4}/', $carPlate);
    }

    public function value(): string
    {
        return $this->value;
    }
}
