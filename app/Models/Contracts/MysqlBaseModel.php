<?php

namespace App\Models\Contracts;

use Medoo\Medoo;

class MysqlBaseModel
{
    protected Medoo $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct(Medoo $db)
    {
        $this->db = $db;
    }

    /** ایجاد رکورد جدید */
    public function create(array $data): int
    {
        $this->db->insert($this->table, $data);
        return (int)$this->db->id();
    }

    /** خواندن یک رکورد با ID */
    public function read(int $id): object
    {
        $data = $this->db->get($this->table, '*', [$this->primaryKey => $id]);
        return is_array($data) ? (object)$data : (object)[];
    }

    /** خواندن چند رکورد */
    public function readAll(array $columns = ['*'], array $where = []): array
    {
        $cols = $columns === ['*'] ? '*' : $columns;
        return $this->db->select($this->table, $cols, $where) ?: [];
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
}
