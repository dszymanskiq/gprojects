<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\Users\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function create(Project $project)
    {
        return Inertia::render('Teacher/Task/Create', [
            'project' => new ProjectResource($project),
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $project->tasks()->create($request->only(['name','description','hours']));
        return redirect()->route('teacher.projects.show',['project' => $project])->with('success', 'Zadanie zostało dodane');
    }

    public function edit(Project $project, Task $task): Response
    {
        $taskResource = new TaskResource($task);
        $taskResource = collect([$taskResource])->map(function($task) use($project) {
            $task->students = [];
            foreach($project->groups as $group) {
                $task->students[$group->id] = $task->students()->whereHas('groups', function ($query) use ($group) {
                    $query->where('groups.id', $group->id);
                })->first();
                if($task->students[$group->id]) {
                    $task->students[$group->id]->group_id = $group->id;
                }
            }
            return $task;
        });
        return Inertia::render('Teacher/Task/Edit', [
            'project' => new ProjectResource($project->load('students.groups', 'groups')),
            'task' => $taskResource->first()
        ]);
    }

    public function update(StoreTaskRequest $request, Project $project, Task $task)
    {
        $task->update($request->only(['name','description', 'hours']));
        $task->students()->detach();
        foreach($request->input('students_ids') as $studentId) {
            $student = Student::find($studentId);
            $task->students()->attach($student);
        }
        return redirect()->route('teacher.projects.show',['project' => $project])->with('success', 'Zadanie zostało zaktualizowane');
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Zadanie zostało usunięte');
    }
}
