<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
            'url' => $this->getFullUrl(),
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'type' => $this->getCustomProperty('type'),
            'uploaded_by' => $this->getCustomProperty('uploaded_by'),
            'uploaded_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
