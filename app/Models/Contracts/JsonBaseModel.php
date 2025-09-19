<?php

namespace App\Models\Contracts;

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

    // Create (Insert)
    public function create(array $data): int
    {
        /**
         * TODO: Unit tests for JsonBaseModel::create()
         *
         * ✅ File mocking and path validation
         * - [*] Mock file_get_contents to simulate reading from a JSON file
         * - [ ] Verify that the file path is correctly constructed using db_folder and table
         *
         * ✅ Data decoding and structure
         * - [ ] Ensure json_decode returns the expected array structure
         * - [ ] Handle cases where json_decode returns null or stdClass
         *
         * ✅ Return value and basic behavior
         * - [ ] Confirm that the method returns an integer (e.g., 1)
         * - [ ] Verify that the new data is appended correctly to the existing array
         *
         * ✅ Input variations
         * - [ ] Test with simple associative arrays
         * - [ ] Test with nested arrays and special characters
         * - [ ] Test with empty arrays and edge cases
         *
         * ✅ File state scenarios
         * - [ ] Handle case where the file is missing (should create a new file)
         * - [ ] Handle case where the file is empty
         * - [ ] Handle case where the file contains invalid JSON
         *
         * ✅ Error simulation
         * - [ ] Simulate file read errors and verify fallback behavior
         * - [ ] Simulate file write errors and ensure graceful failure or exception
         *
         * ✅ Repeatability and consistency
         * - [ ] Test repeated calls to ensure consistent behavior
         * - [ ] Verify that multiple inserts preserve data order and integrity
         */
        // ساخت مسیر فایل
        $table_filepath = $this->db_folder . $this->table . '.json';

        // اگر پوشه وجود نداره، بسازش
        if (!is_dir($this->db_folder) && !mkdir($concurrentDirectory = $this->db_folder, 0777, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        // خواندن فایل JSON (اگر وجود داشته باشه)
        $json = @file_get_contents($table_filepath);
        $table_data = [];

        if ($json !== false) {
            $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            if (is_array($decoded)) {
                $table_data = $decoded;
            }
        }

        // افزودن داده جدید به آرایه
        $table_data[] = $data;

        // ذخیره‌سازی مجدد در فایل
        $new_json = json_encode($table_data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        file_put_contents($table_filepath, $new_json);

        // بازگرداندن عدد (مثلاً شناسه یا تأیید موفقیت)
        return 1;
    }

    // Read (Select) single
    public function read(int $id): object
    {
        return (object)[];
    }

    // Read all
    public function readAll(array $columns = ['*'], array $where = []): array
    {
        return [];
    }

    // Update record
    public function update(int $id, array $data): bool
    {
        return true;
    }

    // Delete record
    public function delete(int $id): bool
    {
        return true;
    }
}
