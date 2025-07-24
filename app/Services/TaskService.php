<?php

namespace App\Services;
use App\Http\Requests\AttachementRequest;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTaskActivities;
use App\Notifications\TaskAssignedNotification;
use Auth;
use Request;
class TaskService
{
    public function getAllTasks()
    {
        return Task::with('project')->latest()->paginate(15);
    }
    public function storeTask(array $data)
    {
        if (isset($data['assigned_to'])) {
            $data['assigned_at'] = now();
        }
        return Task::create($data);
    }

    public function updateTask($id, array $data, ActivityService $service)
    {
        $task = Task::find($id);

        if (!$task) {
            abort(404, 'Task not found');
        }

        if (isset($data['assigned_to'])) {
            $data['assigned_at'] = now();

            $newUser = User::find($data['assigned_to']);
            if ($newUser) {
                $newUser->notify(new TaskAssignedNotification($task));
            } else {
                throw new \Exception("Assigned user not found", 400);
            }
        }

        if (isset($data['status']) && $data['status'] == Task::STATUS_COMPLETED) {
            $activityData = [
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'action' => UserTaskActivities::ACTION_COMPLETED,
            ];
            $service->logTaskCompleteActivity($activityData);
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