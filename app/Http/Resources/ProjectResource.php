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

    public function boot()
    {
        JsonResource::withoutWrapping();
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
            'groups' => $this->groups,
            'due_date' => $this->due_date,
            'closed' => $this->closed,
            'students_count' => $this->whenCounted('students'),
            'students' => $this->whenLoaded('students'),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
        ];
    }
}
