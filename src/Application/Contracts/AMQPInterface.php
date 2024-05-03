<?php

declare(strict_types=1);

namespace Application\Contracts;

interface AMQPInterface
{
    public function connect();
    public function disconnect();
    public function consume(string $queue, \Closure $callback);
    public function publish(string $queue, array $payload): void;
}
