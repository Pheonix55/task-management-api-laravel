<?php

namespace App\Services;
use App\Models\Task;
class TaskService
{
    public function getAllTasks()
    {
        return Task::with('project')->latest()->paginate(15);
    }
    public function storeTask(array $data)
    {
        return Task::create($data);
    }

    public function updateTask($id, array $data)
    {
        $task = Task::find($id);
        if (!$task) {
            throw new \Exception("Task not found", 404);
        }
        $task->update($data);
        $task->save();
        return $task;
    }

    public function deleteTask($id)
    {
        $task = Task::find($id);

        if (!$task) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Task not found');
        }
        return $task->delete();
    }

}