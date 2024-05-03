<?php

declare(strict_types=1);

namespace Infrastructure\Database;

use Infrastructure\Database\Contracts\DbConnectionInterface;
use Infrastructure\Database\Exception\MySqlConnectionException;

class MySqlDbConnectionAdapter implements DbConnectionInterface
{
    private static ?\PDO $pdo = null;
    private string $host;
    private string $database;
    private ?string $charset = 'utf8mb4';
    private ?string $username = null;
    private ?string $password = null;
    private array $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false,
    ];

    public function connect(
        string $host,
        string $database,
        ?string $username = null,
        ?string $password = null,
        ?string $charset = null,
        array $options = []
    ): void {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->charset = $charset ?? $this->charset;
        $this->options = array_merge($this->options, $options);
        self::$pdo = $this->createPDO();
    }

    private function createPDO(): \PDO
    {
        $dns = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
        $pdo = new \PDO(dsn: $dns, username: $this->username, password: $this->password, options: $this->options);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        return $pdo;
    }

    public function getConnect(): \PDO
    {
        if (empty(self::$pdo)) {
            self::$pdo = $this->createPDO();
        }
        return self::$pdo;
    }

    public function disconnect(): void
    {
        self::$pdo = null;
    }

    public function connectTesting(): void
    {
        try {
            $this->host = getenv('DB_HOST_TESTING');
            $this->database = getenv('DB_DATABASE_TESTING');
            $this->username = getenv('DB_USERNAME_TESTING');
            $this->password = getenv('DB_PASSWORD_TESTING');
            self::$pdo = $this->createPDO();
        } catch (\PDOException $e) {
            throw MySqlConnectionException::error(error: $e->getMessage());
        }
    }
}
