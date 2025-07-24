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
    public function show($id, ProjectService $service)
    {
        try {
            $data = $service->getProjectById($id);
            return response()->json(new ProjectResource($data), 200);
        } catch (Throwable $th) {
            return response()->json(['message' => 'something went wrong ' . $th->getMessage()], 500);
        }
    }

    public function update(ProjectRequest $request, ProjectService $service, $id)
    {
        try {
            $data = $service->updateProject($request->validated(), $id);
            if (!$data) {
                return response()->json(['message' => 'data not updated! Project with the id =' . $id . ' not found!'], 200);
            } else {
                return response()->json(new ProjectResource($data), 200);
            }
        } catch (Throwable $th) {
            return response()->json(['message' => 'something went wrong ' . $th->getMessage()], 200);
        }
    }

    public function delete($id, ProjectService $service)
    {
        try {
            if ($service->deleteProject($id)) {
                return response()->json(['success' => true, 'message' => 'Project deleted successfully'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Project  with id =' . $id . ' not found, may already have been deleted'], 200);
            }
        } catch (Throwable $th) {
            return response()->json(['success' => false, 'message' => 'something went wrong ' . $th->getMessage()], 500);
        }
    }
    public function getProjectsByOrganization($id, ProjectService $service)
    {
        try {
            $response = $service->getProjectsByOrganization($id);
            if ($response) {
                return ProjectResource::collection($response);
            } else {
                return response()->json(['success' => false, 'message' => 'projects not found'], 200);
            }
        } catch (Throwable $th) {
            return response()->json(['success' => false, 'message' => 'something went wrong ' . $th->getMessage()], 500);
        }
    }

    public function projectReport(ProjectService $service, $id)
    {
        try {
            $data = $service->projectReport($id);
            return $this->successResponse($data);
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', 500);
        }
    }

    public function topContributorProjectWise(ProjectService $service, $id)
    {
        try {
            $data = $service->topContributorProjectWise($id);
            return $this->successResponse($data);
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', 500);
        }
    }
}
