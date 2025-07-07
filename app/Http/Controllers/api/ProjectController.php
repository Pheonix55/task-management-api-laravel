<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Throwable;

class ProjectController extends Controller
{
    public function index(ProjectService $service)
    {
        try {
            return response()->json($service->getAllProjects(), 200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'something went wrong ' . $th->getMessage()
            ], 500);
        }
    }

    public function store(ProjectRequest $request, ProjectService $service)
    {
        try {
            return new ProjectResource($service->storeProject($request->validated()));
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'something went wrong ' . $th->getMessage()
            ], 500);
        }
    }
}
