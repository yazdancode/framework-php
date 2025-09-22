<?php

namespace App\Models\Contracts;

use Medoo\Medoo;
use RuntimeException;

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
            throw new RuntimeException("Insert failed, ID not generated.");
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

        $this->attributes = $data;
        return (object)$data;
    }

    /** خواندن چند رکورد */
    public function readAll(array $columns = ['*'], array $where = []): array
    {
        $cols = $columns === ['*'] ? '*' : $columns;
        $result = $this->db->select($this->table, $cols, $where);
        return $result ?: [];
    }

    /** بروزرسانی رکورد با ID */
    public function update(int $id, array $data): bool
    {
        $result = $this->db->update($this->table, $data, [$this->primaryKey => $id]);
        return $result !== null && $result->rowCount() > 0;
    }

    /** حذف رکورد با ID */
    public function delete(int $id): bool
    {
        $result = $this->db->delete($this->table, [$this->primaryKey => $id]);
        return $result !== null && $result->rowCount() > 0;
    }

    /** تعداد رکوردها */
    public function count(array $where = []): int
    {
        return (int)$this->db->count($this->table, $where);
    }

    /** جمع یک ستون */
    public function sum(string $column, array $where = []): float
    {
        return (float)$this->db->sum($this->table, $column, $where);
    }

    /** ذخیره رکورد (جدید یا موجود) */
    public function save(): bool
    {
        if (empty($this->attributes)) return false;

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

    /** پر کردن attributes */
    public function fill(array $data): void
    {
        $this->attributes = array_merge($this->attributes, $data);
    }

    /** گرفتن مقدار attribute */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    /** تنظیم مقدار attribute */
    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    /** پیدا کردن رکورد با ستون خاص */
    public function findByColumn(string $column, $value, array $columns = ['*']): array
    {
        return $this->readAll($columns, [$column => $value]);
    }

    /** گرفتن اولین رکورد مطابق شرط */
    public function first(array $where = [], array $columns = ['*']): object
    {
        $data = $this->db->get($this->table, $columns, $where);
        return $data ? (object)$data : (object)[];
    }

    /** بررسی وجود رکورد مطابق شرط */
    public function exists(array $where): bool
    {
        return $this->count($where) > 0;
    }

    /** حذف چند رکورد مطابق شرط */
    public function deleteWhere(array $where): int
    {
        $result = $this->db->delete($this->table, $where);
        return $result ? $result->rowCount() : 0;
    }

    /** آپدیت چند رکورد مطابق شرط */
    public function updateWhere(array $where, array $data): int
    {
        $result = $this->db->update($this->table, $data, $where);
        return $result ? $result->rowCount() : 0;
    }

    /** گرفتن مقادیر یک ستون */
    public function pluck(string $column, array $where = []): array
    {
        $rows = $this->db->select($this->table, $column, $where);
        return $rows ?: [];
    }
}
