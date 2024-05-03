<?php

declare(strict_types=1);

use PhpAmqpLib\Exchange\AMQPExchangeType;

return [
    'queue_name' => getenv('RABBITMQ_QUEUE'),
    'rabbitmq' => [
        'hosts' => [
            [
                'host' => getenv('RABBITMQ_HOST'),
                'port' => getenv('RABBITMQ_PORT'),
                'user' => getenv('RABBITMQ_USER'),
                'password' => getenv('RABBITMQ_PASSWORD'),
                'vhost' => getenv('RABBITMQ_VHOST'),
            ],
        ],
    ],
    'ride' => [
        'exchange' => 'dlx'
    ],
];
