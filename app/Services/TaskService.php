<?php

namespace App\Services;
use App\Http\Requests\AttachementRequest;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Request;
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
        if ($data['assigned_to']) {
            $previousUserId = $task->assigned_to;
            $newUserId = $data['assigned_to'];
            $newUser = User::find($newUserId);
            if ($newUser) {
                $newUser->notify(new TaskAssignedNotification($task));
            }

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


    public function addAttachementToTask(AttachementRequest $request, $id)
    {
        $task = Task::findOrFail($id);

        return $task->addMediaFromRequest('file')
            ->withCustomProperties([
                'uploaded_by' => auth()->id(),
                'type' => $request->input('type', 'general'),
            ])
            ->toMediaCollection('attachments');
    }


    public function listAttachments($id)
    {
        $task = Task::find($id);
        return $task->getMedia('attachments');
    }

    public function deleteAttachments($task_id, $media_id): bool
    {
        $task = Task::findOrFail($task_id);
        $media = $task->media()->where('id', $media_id)->first();
        if (!$media) {
            return false;
        }
        return $media->delete();
    }



}