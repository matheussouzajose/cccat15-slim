<?php

declare(strict_types=1);

namespace Application\Auth\Command;

class SignUp
{
    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly string $cpf,
        private readonly bool $isPassenger,
        private readonly ?bool $isDriver = false,
        private readonly ?string $carPlate = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function isPassenger(): bool
    {
        return $this->isPassenger;
    }

    public function isDriver(): bool
    {
        return $this->isDriver;
    }

    public function getCarPlate(): ?string
    {
        return $this?->carPlate;
    }
}
