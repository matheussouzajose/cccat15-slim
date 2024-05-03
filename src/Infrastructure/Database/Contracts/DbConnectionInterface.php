<?php

declare(strict_types=1);

namespace Infrastructure\Database\Contracts;

interface DbConnectionInterface
{
    public function connect(
        string $host,
        string $database,
        ?string $username = null,
        ?string $password = null,
        ?string $charset = null,
        array $options = []
    );

    public function getConnect();

    public function disconnect(): void;
}
