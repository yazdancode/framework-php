<?php

namespace App\Models\Contracts;

use Medoo\Medoo;
use PDOException;

class MysqlBaseModel
{
    protected Medoo $db;
    protected string $table = '';
    protected string $primaryKey = 'id';
    protected array $attributes = [];

    public function __construct(Medoo $db)
    {
        $this->db = $db;
    }

    /** ایجاد رکورد جدید */
    public function create(array $data): int
    {
        $this->db->insert($this->table, $data);
        $id = (int)$this->db->id();
        if ($id <= 0) {
            throw new \RuntimeException("Insert failed, ID not generated.");
        }
        return $id;
    }

    /** خواندن یک رکورد با ID */
    public function read(int $id): object
    {
        $data = $this->db->get($this->table, '*', [$this->primaryKey => $id]);

        if (!$data) {
            return (object)[];
        }

        foreach ($data as $col => $val) {
            $this->attributes[$col] = $val;
        }

        return (object)$data;
    }

    /** خواندن چند رکورد */
    public function readAll(array $columns = ['*'], array $where = []): array
    {
        $cols = $columns === ['*'] ? '*' : $columns;
        $result = $this->db->select($this->table, $cols, $where);
        return $result ?: [];
    }

    /** بروزرسانی رکورد */
    public function update(int $id, array $data): bool
    {
        $result = $this->db->update($this->table, $data, [$this->primaryKey => $id]);
        return $result !== null && $result->rowCount() > 0;
    }

    /** حذف رکورد */
    public function delete(int $id): bool
    {
        $result = $this->db->delete($this->table, [$this->primaryKey => $id]);
        return $result !== null && $result->rowCount() > 0;
    }

    public function count(array $where = []): int
    {
        return (int)$this->db->count($this->table, $where);
    }

    public function sum(string $column, array $where = []): float
    {
        return (float)$this->db->sum($this->table, $column, $where);
    }

    public function save(): bool
    {
        if (empty($this->attributes)) {
            return false;
        }

        if (isset($this->attributes[$this->primaryKey])) {
            $id = $this->attributes[$this->primaryKey];
            $data = $this->attributes;
            unset($data[$this->primaryKey]);
            return $this->update($id, $data);
        }

        $id = $this->create($this->attributes);
        if ($id > 0) {
            $this->attributes[$this->primaryKey] = $id;
            return true;
        }
        return false;
    }

    public function fill(array $data): void
    {
        $this->attributes = array_merge($this->attributes, $data);
    }

    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }
}
