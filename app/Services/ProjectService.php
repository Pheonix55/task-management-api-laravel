<?php
namespace App\Services;

use App\Http\Resources\ProjectResource;
use App\Models\Project;

class ProjectService
{


    public function getAllProjects()
    {
        return ProjectResource::collection(Project::latest()->paginate(15));
    }

    public function storeProject(array $data)
    {
        return Project::create($data);
    }
}