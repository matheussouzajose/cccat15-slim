<?php

declare(strict_types=1);

namespace Main\Config;

use Infrastructure\Database\Contracts\DbConnectionInterface;
use Infrastructure\Http\HttpServerInterface;
use Psr\Container\ContainerInterface;

class App
{
    public function __construct(
        protected HttpServerInterface $httpServer,
        protected DbConnectionInterface $dbConnection,
        protected ContainerInterface $container
    ) {
        $this->registerRoutes();
        $this->connectDb();
    }

    private function registerRoutes(): void
    {
        $routesDirectory = __DIR__ . '/../Routes/Api';
        $routes = scandir($routesDirectory);
        foreach ($routes as $route) {
            if ($route !== '.' && $route !== '..' && is_file($routesDirectory . '/' . $route)) {
                $callback = require $routesDirectory . '/' . $route;
                if ($callback instanceof \Closure) {
                    $callback($this->httpServer);
                }
            }
        }
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
