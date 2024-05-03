<?php

declare(strict_types=1);

namespace Tests;

use Infrastructure\Database\PDOConnectionAdapter;
use PHPUnit\Framework\TestCase as BaseTestCase;

class DatabaseTestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    private function setUpDatabase(): void
    {
        foreach ($this->getTables() as $table) {
            PDOConnectionAdapter::getConnection()->exec("TRUNCATE TABLE $table");
            PDOConnectionAdapter::getConnection()->exec("SET foreign_key_checks = 0");
            PDOConnectionAdapter::getConnection()->exec("TRUNCATE TABLE $table");
            PDOConnectionAdapter::getConnection()->exec("SET foreign_key_checks = 1");
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        foreach ($this->getTables() as $table) {
            PDOConnectionAdapter::getConnection()->exec("TRUNCATE TABLE $table");
        }
        PDOConnectionAdapter::disconnect();
    }

    private function getTables(): bool|array
    {
        $stmt = PDOConnectionAdapter::getConnection()->query("SHOW TABLES");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
