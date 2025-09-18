<?php

namespace App\Models\Contracts;

interface CrudInterface
{
    // Create (Insert)
    public function create(array $data): int;

    // Read (Select) single or multiple
    public function read(int $id): object;
    public function readAll(array $columns = ['*'], array $where = []): array;

    // Update record
    public function update(int $id, array $data): bool;

    // Delete record
    public function delete(int $id): bool;
}
