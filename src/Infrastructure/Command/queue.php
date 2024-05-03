<?php

use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Queue\RabbitMQAdapter;
use Main\Config\Queue;

require_once __DIR__ . '/../../../vendor/autoload.php';

$queue = new Queue(queue: new RabbitMQAdapter(), dbConnection: new MySqlDbConnectionAdapter());
