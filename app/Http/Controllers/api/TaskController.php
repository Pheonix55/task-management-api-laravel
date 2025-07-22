<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttachementRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\AttachmentResource;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Throwable;

class TaskController extends Controller
{
    public function index(TaskService $service)
    {
        return $this->successResponse(TaskResource::collection($service->getAllTasks()));
    }

    public function store(TaskService $service, TaskRequest $request)
    {
        try {
            $data = $service->storeTask($request->validated());
            return $this->successResponse($data);
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', 500);
        }
    }
    public function update(TaskService $service, UpdateTaskRequest $request, $id)
    {
        try {
            $data = $service->updateTask($id, $request->validated());
            
            return $this->successResponse($data, 'task updated successfully');
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', 500);
        }
    }

    public function destroy($id, TaskService $service)
    {
        $service->deleteTask($id);
        return $this->successResponse('task deleted successfully');
    }

    public function addAttachments(TaskService $service, AttachementRequest $request, $id)
    {
        try {
            $media = $service->addAttachementToTask($request, $id); // full request passed
            return $this->successResponse(new AttachmentResource($media), 'Attachment uploaded successfully', 201);
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage(), code: 500);
        }
    }
    public function listAttachments(TaskService $service, $id)
    {
        try {

            return $this->successResponse(
                AttachmentResource::collection($service->listAttachments($id))
            );
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function destroyAttachment($taskId, $mediaId, TaskService $service)
    {
        $deleted = $service->deleteAttachments($taskId, $mediaId);

        if ($deleted) {
            return $this->successResponse(message: 'Attachment deleted successfully');
        }

        return $this->errorResponse(message: 'Attachment not found');
    }


}
