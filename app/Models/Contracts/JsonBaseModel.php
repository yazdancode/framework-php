<?php

namespace App\Models\Contracts;

use JsonException;
use RuntimeException;

class JsonBaseModel extends BaseModel
{
    private string $db_folder;
    protected string $table;

    public function __construct(string $table = '')
    {
        $this->db_folder = BASEPATH . "storage/jsondb/";
        $this->table = $table;
    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getFilePath(): string
    {
        $tableName = $this->table;
        if (!str_ends_with($tableName, '.json')) {
            $tableName .= '.json';
        }
        return $this->db_folder . $tableName;
    }

    private function writeJson(string $path, array $data): void
    {
        try {
            $json = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (JsonException) {

        }
        if (file_put_contents($path, $json) === false) {
            throw new RuntimeException("Failed to write to file: $path");
        }
    }

    private function readJson(): array
    {
        $path = $this->getFilePath();
        if (!file_exists($path)) {
            return [];
        }

        try {
            $json = file_get_contents($path);
            return $json && trim($json) !== ''
                ? json_decode($json, true, 512, JSON_THROW_ON_ERROR)
                : [];
        } catch (JsonException | RuntimeException) {
            return [];
        }
    }

    // Create
    public function create(array $data): int
    {
        $path = $this->getFilePath();
        if (!is_dir($this->db_folder)) {
            mkdir($this->db_folder, 0777, true);
        }

        $table_data = $this->readJson();
        $table_data[] = $data;

        try {
            $this->writeJson($path, $table_data);
            return 1;
        } catch (RuntimeException) {
            return 0;
        }
    }

    // Read single
    public function read(int $id): object
    {
        $data = $this->readJson();
        return isset($data[$id]) ? (object)$data[$id] : (object)[];
    }

    // Read all
    public function readAll(array $columns = ['*'], array $where = []): array
    {
        return $this->readJson();
    }

    public function get(array $columns, array $where): object
    {
        $results = $this->filterData($columns, $where);
        return !empty($results) ? (object)$results[0] : (object)[];
    }

    public function getAll(array $columns, array $where): array
    {
        return $this->filterData($columns, $where);
    }

    private function filterData(array $columns, array $where): array
    {
        $data = $this->readJson();

        // Apply where filters
        $filtered = array_filter($data, static function ($item) use ($where) {
            if (array_any($where, static fn($value, $key) => !isset($item[$key]) || $item[$key] != $value)) {
                return false;
            }
            return true;
        });

        // Select columns
        if ($columns !== ['*']) {
            $filtered = array_map(static function ($item) use ($columns) {
                return array_intersect_key($item, array_flip($columns));
            }, $filtered);
        }

        return array_values($filtered);
    }



    // Update
    public function update(int $id, array $data): bool
    {
        $table_data = $this->readJson();
        if (!isset($table_data[$id])) {
            return false;
        }

        $table_data[$id] = array_merge($table_data[$id], $data);

        try {
            $this->writeJson($this->getFilePath(), $table_data);
            return true;
        } catch (RuntimeException) {
            return false;
        }
    }

    // Delete
    public function delete(int $id): bool
    {
        $table_data = $this->readJson();
        if (!isset($table_data[$id])) {
            return false;
        }

        unset($table_data[$id]);
        $table_data = array_values($table_data); // reindex

        try {
            $this->writeJson($this->getFilePath(), $table_data);
            return true;
        } catch (RuntimeException) {
            return false;
        }
    }
}
