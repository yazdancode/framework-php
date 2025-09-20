<?php

namespace App\Models\Contracts;
use Medoo\Medoo;

class MysqlBaseModel extends BaseModel
{
    protected Medoo $db;
    public function __construct(Medoo $db)
    {
        parent::__construct();
        $this->db = $db;
    }

    public function create(array $data): int
    {
        $this->db->insert($this->table, $data);

        return (int)$this->db->id();
    }

    public function read(int $id): object
    {
        $data = $this->db->get($this->table, '*', [$this->primaryKey => $id]);
        return $data ? (object)$data : (object)[];
    }

    public function readAll(array $columns = ['*'], array $where = []): array
    {
        $cols = $columns === ['*'] ? '*' : $columns;
        return $this->db->select($this->table, $cols, $where);
    }


    public function update(int $id, array $data): bool
    {
        $where = [$this->primaryKey => $id];

        $result = $this->db->update($this->table, $data, $where);

        return $result?->rowCount() > 0;
    }


    public function delete(int $id): bool
    {
        $where = [$this->primaryKey => $id];
        $result = $this->db->delete($this->table, $where);
        if ($result === null) {
            return false;
        }
        return $result->rowCount() > 0;
    }

}