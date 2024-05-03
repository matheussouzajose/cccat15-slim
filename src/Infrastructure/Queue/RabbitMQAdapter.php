<?php

declare(strict_types=1);

namespace Infrastructure\Queue;

use Application\Contracts\AMQPInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQAdapter implements AMQPInterface
{
    private static ?AMQPStreamConnection $connection = null;
    private AMQPChannel $channel;

    public array $config = [];
    public string $exchange;

    public array $properties = [
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        'content_type' => 'text/plain'
    ];

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../../config/microservices.php';
        $this->exchange = $this->config['ride']['exchange'];
    }

    /**
     * @throws \Exception
     */
    public function connect(): void
    {
        $configs = $this->config['rabbitmq']['hosts'][0];
        self::$connection = new AMQPStreamConnection(
            host: $configs['host'],
            port: $configs['port'],
            user: $configs['user'],
            password: $configs['password'],
            vhost: $configs['vhost']
        );
        $this->channel = self::$connection->channel();
    }

    /**
     * @throws \Exception
     */
    public function disconnect(): void
    {
        self::$connection->close();
    }

    /**
     * @throws \Exception
     */
    public function consume(string $queue, \Closure $callback): void
    {
        $this->connect();
        $this->declareQueue(queue: $queue);
        $this->bindQueue(queue: $queue, exchange: $this->exchange, routingKey: $queue);
        $callback = function ($message) use ($callback) {
            try {
                $body = json_decode($message->body);
                $callback($body);
                $message->ack();
            } catch (\Throwable $throwable) {
                var_dump('error', $throwable->getMessage());
            }
        };
        $this->channel->basic_consume(queue: $queue, callback: $callback);
    }

    /**
     * @throws \Exception
     */
    public function isConsuming(): void
    {
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
        $this->closeChannel();
        $this->disconnect();
    }

    /**
     * @throws \Exception
     */
    public function publish(string $queue, array $payload): void
    {
        $this->connect();
        $this->declareQueue(queue: $queue);
        $this->declareExchange(exchange: $this->exchange, type: AMQPExchangeType::TOPIC);
        $this->bindQueue(queue: $queue, exchange: $this->exchange, routingKey: $queue);
        $message = new AMQPMessage(body: json_encode($payload), properties: $this->properties);
        $this->channel->basic_publish(msg: $message, exchange: $this->exchange, routing_key: $queue);
        $this->closeChannel();
        $this->disconnect();
    }

    public function closeChannel(): void
    {
        $this->channel->close();
    }

    private function declareQueue(string $queue): void
    {
        $this->channel->queue_declare(
            queue: $queue,
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false
        );
    }

    private function declareExchange(string $exchange, string $type = AMQPExchangeType::DIRECT): void
    {
        $this->channel->exchange_declare(
            exchange: $exchange,
            type: $type,
            passive: false,
            durable: true,
            auto_delete: false
        );
    }

    private function bindQueue(string $queue, string $exchange, string $routingKey = ''): void
    {
        $this->channel->queue_bind(queue: $queue, exchange: $exchange, routing_key: $routingKey);
    }

//    public function publishFanout(string $queue, array $payload): void
//    {
//        $exchange = $this->config['ride']['exchange'];
//        $this->declareQueue(queue: $queue);
//        $this->declareExchange(exchange: $exchange, type: AMQPExchangeType::FANOUT);
//        $this->bindQueue(queue: $queue, exchange: $exchange);
//        $message = new AMQPMessage(json_encode($payload), ['content_type' => 'text/plain']);
//        $this->channel->basic_publish($message, $exchange);
//        $this->closeChannel();
//        $this->disconnect();
//    }

}
