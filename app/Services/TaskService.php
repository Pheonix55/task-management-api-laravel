<?php

use App\Models\Task;

class TaskService
{
    public function getAllTasks()
    {
        return Task::latest()->paginate(15);
    }
    public function storeTask(array $data)
    {
        return Task::create($data);
    }
}