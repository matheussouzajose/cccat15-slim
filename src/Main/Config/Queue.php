<?php

declare(strict_types=1);

namespace Main\Config;

use Application\Contracts\AMQPInterface;
use Infrastructure\Database\Contracts\DbConnectionInterface;

class Queue
{
    public function __construct(
        private readonly AMQPInterface $queue,
        private readonly DbConnectionInterface $dbConnection
    ) {
        $this->connectDb();
        $this->consumers();
    }

    public function consumers(): void
    {
        $queuesDirectory = __DIR__ . '/../Queues';
        $queues = scandir($queuesDirectory);
        foreach ($queues as $queue) {
            if ($queue !== '.' && $queue !== '..' && is_file($queuesDirectory . '/' . $queue)) {
                $callback = require $queuesDirectory . '/' . $queue;
                if ($callback instanceof \Closure) {
                    $callback($this->queue);
                }
            }
        }
        $this->queue->isConsuming();
    }

    private function connectDb(): void
    {
        $this->dbConnection->connect(
            host: getenv('DB_HOST'),
            database: getenv('DB_DATABASE'),
            username: getenv('DB_USERNAME'),
            password: getenv('DB_PASSWORD')
        );
    }
}
