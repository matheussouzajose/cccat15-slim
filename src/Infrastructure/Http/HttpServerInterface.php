<?php

declare(strict_types=1);

namespace Infrastructure\Http;

interface HttpServerInterface
{
    public function register(string $method, string $url, $callback, array $middlewares = []): void;
    public function listen(): void;
}
