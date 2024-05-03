<?php

declare(strict_types=1);

namespace Tests\Stubs;

use Application\Contracts\AMQPInterface;

class RabbitMQAdapterStub implements AMQPInterface
{

    public function connect()
    {
        // TODO: Implement connect() method.
    }

    public function disconnect()
    {
        // TODO: Implement disconnect() method.
    }

    public function consume(string $queue, \Closure $callback)
    {
        // TODO: Implement consume() method.
    }

    public function publish(string $queue, array $payload): void
    {
        var_dump($queue, $payload);
    }
}
