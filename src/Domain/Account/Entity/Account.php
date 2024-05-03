<?php

declare(strict_types=1);

namespace Domain\Account\Entity;

use Domain\Account\ValueObject\CarPlate;
use Domain\Account\ValueObject\Cpf;
use Domain\Account\ValueObject\Email;
use Domain\Account\ValueObject\Name;
use Domain\Shared\Entity\AggregateRoot;
use Domain\Shared\ValueObject\Uuid;

class Account extends AggregateRoot
{
    private readonly Uuid $accountId;
    private Name $name;
    private Email $email;
    private Cpf $cpf;
    private ?CarPlate $carPlate = null;
    private \DateTimeInterface $createdAt;
    private \DateTimeInterface $updatedAt;

    /**
     * @throws \Exception
     */
    private function __construct(
        string $accountId,
        string $name,
        string $email,
        string $cpf,
        private readonly bool $isPassenger,
        private readonly bool $isDriver,
        string $createdAt,
        string $updatedAt,
        ?string $carPlate = null
    ) {
        $this->accountId = new Uuid(value: $accountId);
        $this->name = new Name(value: $name);
        $this->email = new Email(value: $email);
        $this->cpf = new Cpf(value: $cpf);
        $this->createdAt = new \DateTime($createdAt);
        $this->updatedAt = new \DateTime($updatedAt);
        if ($isDriver && $carPlate) {
            $this->carPlate = new CarPlate($carPlate);
        }
    }

    /**
     * @throws \Exception
     */
    public static function create(
        string $name,
        string $email,
        string $cpf,
        bool $isPassenger,
        bool $isDriver = false,
        ?string $carPlate = null
    ): Account {
        $createdAt = (new \DateTime())->format('Y-m-d H:i:s');
        return new Account(
            accountId: Uuid::random()->value(),
            name: $name,
            email: $email,
            cpf: $cpf,
            isPassenger: $isPassenger,
            isDriver: $isDriver,
            createdAt: $createdAt,
            updatedAt: $createdAt,
            carPlate: $carPlate
        );
    }

    /**
     * @throws \Exception
     */
    public static function restore(
        string $accountId,
        string $name,
        string $email,
        string $cpf,
        bool $isPassenger,
        bool $isDriver,
        string $createdAt,
        string $updatedAt,
        ?string $carPlate = null,
    ): Account {
        return new Account(
            accountId: $accountId,
            name: $name,
            email: $email,
            cpf: $cpf,
            isPassenger: $isPassenger,
            isDriver: $isDriver,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            carPlate: $carPlate
        );
    }

    public function accountId(): string
    {
        return $this->accountId->value();
    }

    public function name(): string
    {
        return $this->name->value();
    }

    public function email(): string
    {
        return $this->email->value();
    }

    public function cpf(): string
    {
        return $this->cpf->value();
    }

    public function carPlate(): ?string
    {
        return $this->carPlate?->value();
    }

    public function isPassenger(): bool
    {
        return $this->isPassenger;
    }

    public function isDriver(): bool
    {
        return $this->isDriver;
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }

    public function updatedAt(): string
    {
        return $this->updatedAt->format('Y-m-d H:i:s');
    }

    public function changeName(string $name): void
    {
        $this->name = new Name(value: $name);
        $this->updatedAt = new \DateTime();
    }

    public function changeEmail(string $email): void
    {
        $this->email = new Email(value: $email);
        $this->updatedAt = new \DateTime();
    }

    public function changeCpf(string $cpf): void
    {
        $this->cpf = new Cpf(value: $cpf);
        $this->updatedAt = new \DateTime();
    }

    public function changeCarPlate(string $carPlate): void
    {
        if ($this->isDriver) {
            $this->carPlate = new CarPlate(value: $carPlate);
            $this->updatedAt = new \DateTime();
        }
    }

    public function toArray(): array
    {
        return [
            'accountId' => $this->accountId(),
            'name' => $this->name(),
            'email' => $this->email(),
            'cpf' => $this->cpf(),
            'carPlate' => $this->carPlate(),
            'isPassenger' => $this->isPassenger(),
            'isDriver' => $this->isDriver(),
            'createdAt' => $this->createdAt(),
            'updatedAt' => $this->updatedAt(),
        ];
    }
}
