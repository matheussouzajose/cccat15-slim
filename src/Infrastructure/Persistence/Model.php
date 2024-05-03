<?php

declare(strict_types=1);

namespace Infrastructure\Persistence;

use Infrastructure\Database\MySqlDbConnectionAdapter;

abstract class Model
{
    protected string $table;
    protected array $protected;
    protected array $required;
    protected ?object $data = null;
    protected ?\PDOException $fail = null;
    protected ?string $query = null;
    protected array $params = [];
    protected ?string $order = null;
    protected ?string $limit = null;
    protected ?string $offset = null;

    public function __construct(protected MySqlDbConnectionAdapter $databaseConnection)
    {
    }

    public function __set(string $name, $value): void
    {
        if (!isset($this->data)) {
            $this->data = new \stdClass();
        }
        $this->data->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    public function data(): ?object
    {
        return $this->data;
    }

    public function find(
        ?string $terms = null,
        string $params = '',
        string $columns = "*"
    ): Model {
        $this->params = [];
        if ($terms) {
            $this->query = "SELECT {$columns} FROM {$this->table} WHERE {$terms}";
            parse_str($params, $this->params);
            return $this;
        }

        $this->query = "SELECT {$columns} FROM {$this->table}";
        return $this;
    }

    public function findById(string $id, string $columns = "*"): ?Model
    {
        $find = $this->find(terms: "id = :id", params: "id={$id}", columns: $columns);
        return $find->fetch();
    }

    public function order(string $columnOrder): Model
    {
        $this->order = " ORDER BY {$columnOrder}";
        return $this;
    }

    public function limit(int $limit): Model
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    public function offset(int $offset): Model
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    public function fetch(): mixed
    {
        $stmt = $this->databaseConnection->getConnect()->prepare(
            query: $this->query . $this->order . $this->limit . $this->offset
        );
        $stmt->execute(params: $this->params);
        if (!$stmt->rowCount()) {
            return null;
        }
        return $stmt->fetchObject();
    }

    public function fetchAll(): array|null|false
    {
        $stmt = $this->databaseConnection->getConnect()->prepare(
            query: $this->query . $this->order . $this->limit . $this->offset
        );
        $stmt->execute($this->params);
        if (!$stmt->rowCount()) {
            return null;
        }
        return $stmt->fetchAll(mode: \PDO::FETCH_CLASS, args: static::class);
    }

    public function create(array $data): void
    {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $stmt = $this->databaseConnection->getConnect()->prepare(
            query: "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})"
        );
        $stmt->execute($data);
    }

    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value));
        }
        return $filter;
    }

    public function update(array $data, string $terms, string $params = ''): bool
    {
        $dataSet = [];
        foreach ($data as $bind => $value) {
            $dataSet[] = "{$bind} = :{$bind}";
        }

        $dataSet = implode(", ", $dataSet);
        parse_str($params, $this->params);
        $stmt = $this->databaseConnection->getConnect()->prepare(
            query: "UPDATE {$this->table} SET {$dataSet} WHERE {$terms}"
        );
        $stmt->execute($this->filter(data: array_merge($data, $this->params)));
        return (bool)($stmt->rowCount() ?? 1);
    }

    public function delete(string $terms, array $params): bool
    {
        $stmt = $this->databaseConnection->getConnect()->prepare(
            query: "DELETE FROM {$this->table} WHERE {$terms}"
        );
        return $stmt->execute($params);
    }
}
