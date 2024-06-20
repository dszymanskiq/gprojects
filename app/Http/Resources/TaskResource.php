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
            'student_id' => $this->student_id,
            'name' => $this->name,
            'hours' => $this->hours
            'studentTimerEntries' => $this->timerEntries()->where('student_id', auth()->user()->id)->count() ? TimerEntryResource::collection($this->timerEntries()->where('student_id', auth()->user()->id)->get()): null,
            'timerEntries' => $this->timerEntries ? TimerEntryResource::collection($this->timerEntries): null,
        ];
    }
}
