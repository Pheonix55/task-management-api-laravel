<?php
namespace App\Services;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Auth;
use Error;
use Throwable;

class ProjectService
{


    public function getAllProjects()
    {
        return ProjectResource::collection(Project::latest()->paginate(15));
    }
    public function getProjectsByOrganization($id)
    {
        $data = Project::with('tasks', 'organization')
            ->where('organization_id', $id)
            ->paginate(15);

        return $data;

    }

    public function storeProject(array $data)
    {
        $data['tech_stack'] = json_encode($data['tech_stack']);
        $data['created_by'] = Auth::user()->id;
        $data['organization_id'] = Auth::user()->organization_id;
        dd($data);
        return Project::create($data);
    }
    public function getProjectById($id)
    {
        return new ProjectResource(Project::find($id));
    }

    public function updateProject(array $data, $id)
    {
        $project = $this->getProjectById($id);
        if (!$project->resource) {
            return null;
        }
        if (isset($data['tech_stack'])) {
            $data['tech_stack'] = json_encode($data['tech_stack']);
        }

        $project->update($data);

        return $project;
    }

    public function deleteProject($id)
    {
        $data = $this->getProjectById($id);
        if ($data->resource) {
            $data->delete();
            return true;
        } else {
            return false;
        }
    }

}