<?php

declare(strict_types=1);

namespace Infrastructure\Database;

use Infrastructure\Database\Contracts\DatabaseConnectionInterface;

class PDOConnectionAdapter
{
    protected static ?\PDO $pdo = null;

    public function __construct()
    {
    }

    public function __clone(): void
    {
    }

    public static function getConnection(): \PDO
    {
        if (empty(self::$pdo)) {
            self::$pdo = self::connect();
        }
        return self::$pdo;
    }

    public static function connect(): \PDO
    {
        $connection = getenv('DB_CONNECTION');
        $config = self::config()[$connection];
        try {
            $pdo = new \PDO(
                dsn: $config['dsn'],
                username: $config['username'],
                password: $config['password'],
                options: $config['options']
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    private static function config(): array
    {
        $host = (getenv('DB_HOST_TESTING') ? getenv('DB_HOST_TESTING') : getenv('DB_HOST')) ?: null;
        $database = (getenv('DB_DATABASE_TESTING') ? getenv('DB_DATABASE_TESTING') : getenv('DB_DATABASE')) ?: null;
        $username = (getenv('DB_USERNAME_TESTING') ? getenv('DB_USERNAME_TESTING') : getenv('DB_USERNAME')) ?: null;
        $password = (getenv('DB_PASSWORD_TESTING') ? getenv('DB_PASSWORD_TESTING') : getenv('DB_PASSWORD')) ?: null;
        $charset = (getenv('DB_CHARSET_TESTING') ? getenv('DB_CHARSET_TESTING') : getenv('DB_CHARSET')) ?: null;

        return [
            'sqlite' => [
                'dsn' => "sqlite::memory:",
                'username' => $username,
                'password' => $password,
                'options' => self::getOptions()
            ],
            'mysql' => [
                'dsn' => "mysql:host=$host;dbname=$database;charset=$charset",
                'username' => $username,
                'password' => $password,
                'options' => self::getOptions()
            ],
        ];
    }

    private static function getOptions(): array
    {
        return [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }

    public static function disconnect(): void
    {
        self::$pdo = null;
    }
}
