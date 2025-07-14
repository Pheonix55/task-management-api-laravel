<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'detail' => $this->detail,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'project_id' => $this->project_id,
            'project' => $this->relationLoaded('project')
                ? $this->project->map(function ($project) {
                    return [
                        'id' => $project->id,
                        'name' => $project->name,
                        'description' => $project->description,
                        'details' => $project->details,
                        'tech_stack' => json_decode($project->tech_stack),
                    ];
                })
                : null,
        ];
    }
}
