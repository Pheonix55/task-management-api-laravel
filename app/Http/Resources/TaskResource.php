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
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'detail' => $this->detail,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'status' => $this->status,
            'priority' => $this->priority,
            'assigned_at' => $this->assigned_at,
            'project_id' => $this->project_id,
            'project' => $this->relationLoaded('project') && $this->project
                ? [
                    'id' => $this->project->id,
                    'name' => $this->project->name,
                    'description' => $this->project->description,
                    'details' => $this->project->details,
                    'tech_stack' => json_decode($this->project->tech_stack),
                ]
                : null,
        ];
    }
}
