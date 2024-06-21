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
            'description' => $this->description,
            'hours' => $this->hours,
            'students' => $this->students,
            'studentTimerEntries' => $this->timerEntries()->where('student_id', auth()->user()->id)->count() ? TimerEntryResource::collection($this->timerEntries()->where('student_id', auth()->user()->id)->get()): null,
            'timerEntries' => $this->timerEntries ? TimerEntryResource::collection($this->timerEntries): null,
        ];
    }
}
