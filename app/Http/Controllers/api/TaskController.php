<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
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
            return $this->successResponse($data);
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', 500);
        }
    }

    public function destroy($id, TaskService $service)
    {
        $service->deleteTask($id);
        return $this->successResponse('task deleted successfully');
    }

}
