<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'details' => $this->details,
            'tech_stack' => json_decode($this->tech_stack),

            'tasks' => $this->relationLoaded('tasks')
                ? $this->tasks->map(function ($task) {
                    return [
                        'name' => $task->name,
                        'type' => $task->type,
                        'detail' => $task->detail,
                        'description' => $task->description,
                        'assigned_to' => $task->assigned_to,
                        'project_id' => $task->project_id,
                    ];
                })
                : null,

            'organization' => $this->relationLoaded('organization') && $this->organization
                ? [
                    'id' => $this->organization->id,
                    'name' => $this->organization->name,
                ]
                : null,
        ];
    }

}
