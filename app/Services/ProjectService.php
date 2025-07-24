<?php
namespace App\Services;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\UserTaskActivities;
use Auth;
use DB;
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


    public function projectReport($id)
    {
        $dateToday = now();
        $project = Project::findOrFail($id);

        $tasksQuery = Task::where('project_id', $id);

        $total_completed = (clone $tasksQuery)->where('status', Task::STATUS_COMPLETED)->count();
        $total_pending = (clone $tasksQuery)->where('status', Task::STATUS_PENDING)->count();
        $total_progress = (clone $tasksQuery)->where('status', Task::STATUS_IN_PROGRESS)->count();
        $overdue_tasks = (clone $tasksQuery)
            ->where('deadline', '<', $dateToday)
            ->whereNotNull('deadline')
            ->count();

        $total = $total_completed + $total_pending + $total_progress;
        $completion_rate = $total > 0 ? round(($total_completed / $total) * 100, 2) : 0;

        $high_p = (clone $tasksQuery)->where('priority', Task::PRIORITY_HIGH)->count();
        $low_p = (clone $tasksQuery)->where('priority', Task::PRIORITY_LOW)->count();
        $medium_p = (clone $tasksQuery)->where('priority', Task::PRIORITY_MEDIUM)->count();
        return [
            'tasksComplete' => $total_completed,
            'tasksPending' => $total_pending,
            'tasksInProgress' => $total_progress,
            'totalTasks' => $total_completed + $total_pending + $total_progress,
            'overdueTasks' => $overdue_tasks,
            'completionRate' => round($completion_rate, 2),
            'priority' => [
                'high' => $high_p,
                'medium' => $medium_p,
                'low' => $low_p,
            ],
            'reportGeneratedAt' => now()->toIso8601String(),
        ];

    }


    public function topContributorProjectWise($projectId)
    {
        $top = UserTaskActivities::select('user_id', DB::raw('COUNT(*) as total_completed'))
            ->where('action', UserTaskActivities::ACTION_COMPLETED)
            ->whereHas('task', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->groupBy('user_id')
            ->orderByDesc('total_completed')
            ->with('user')
            ->first();

        if (!$top) {
            return response()->json(['message' => 'No contributors found for this project'], 404);
        }
        return [
            "top_contributor" => [
                "user" => new UserResource($top->user),
                "completed_tasks" => $top->total_completed
            ]
        ];
    }



}