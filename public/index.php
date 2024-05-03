<?php

use DI\Container;
use Infrastructure\Database\MySqlDbConnectionAdapter;
use Infrastructure\Http\SlimServerHttp;
use Main\Config\App;

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..');
$dotenv->load();

$container = new Container();
$container->set('service', function () {
    return 'service';
});
$httpServer = new SlimServerHttp(container: $container);
$dbConnection = new MySqlDbConnectionAdapter();

new App(httpServer: $httpServer, dbConnection: $dbConnection, container: $container);
$httpServer->listen();
