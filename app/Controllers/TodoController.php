<?php

namespace App\Controllers;

class TodoController
{
    public function list(): void
    {
        view('todo.list', $this->getTaskListData());
    }

    public function add(): void
    {
        view('todo.add');
    }

    public function remove(): void
    {
        view('todo.remove');
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
