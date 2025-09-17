<?php

namespace App\Controllers;

use Yazdan\Helpers\ViewHelper;

class TodoController
{
    public function list(): void
    {
        ViewHelper::view('todo.list', $this->getTaskListData());
    }

    public function add(): void
    {
        ViewHelper::view('todo.add');
    }

    public function remove(): void
    {
        ViewHelper::view('todo.remove');
    }

    private function getTaskListData(): array
    {
        return [
            'tasks' => [
                'First Task',
                'Second Task',
                '7th Task',
                'Test Task',
                'another Task'
            ],
            'title' => 'لیست تسک‌ها',
        ];
    }
}
