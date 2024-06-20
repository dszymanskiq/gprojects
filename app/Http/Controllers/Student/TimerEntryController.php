<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;

class TimerEntryController extends Controller
{
    public function start(Project $project, Task $task): RedirectResponse
    {
        $timerEntry = auth()->user()->timerEntries()->where('task_id', $task->id)->whereNull('end_at')->first();
        if(!empty($timerEntry)) {
            return redirect()->route('student.projects.show', $project)->with('error', 'Timer dla tego zadania jest już uruchomiony');
        }
        auth()->user()->timerEntries()->create([
            'task_id' => $task->id,
            'start_at' => now()
        ]);
        return redirect()->route('student.projects.show', $project)->with('success', 'Timer uruchomiony');
    }

    public function stop(Project $project, Task $task): RedirectResponse
    {
        $timerEntry = auth()->user()->timerEntries()->where('task_id', $task->id)->whereNull('end_at')->first();
        if(empty($timerEntry)) {
            return redirect()->route('student.projects.show', $project)->with('error', 'Nie znaleziono aktywnego timera');
        }
        $timerEntry->update([
            'end_at' => now()
        ]);
        return redirect()->route('student.projects.show', $project)->with('success', 'Timer zatrzymany');
    }
}
