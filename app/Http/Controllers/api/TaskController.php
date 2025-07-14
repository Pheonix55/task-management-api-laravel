<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use TaskService;
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

}
