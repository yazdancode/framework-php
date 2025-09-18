<?php 

namespace App\Models\Contracts;

abstract class BaseModel implements CrudInterface
{
    protected \PDO $connection;
    protected string $table;
    protected string $primaryKey = 'id';
    protected int $pageSize = 10;
    protected array $attributes = [];

    protected function __construct()
    {
        try {
            $host = "127.0.0.1";
            $db   = "my_database";
            $user = "root";
            $pass = "secret";
            $charset = "utf8mb4";

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->connection = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    protected function getAttribute(string $key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }

    protected function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    abstract public function create(array $data): int;
    abstract public function read(int $id): object;
    abstract public function readAll(array $columns = ['*'], array $where = []): array;
    abstract public function update(int $id, array $data): bool;
    abstract public function delete(int $id): bool;
}
