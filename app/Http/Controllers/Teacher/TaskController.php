<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
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
        $project->tasks()->create($request->only(['name','hours']));
        return redirect()->route('teacher.projects.show',['project' => $project]);
    }

    public function edit(Project $project, Task $task): Response
    {
        return Inertia::render('Teacher/Task/Edit', [
            'project' => new ProjectResource($project),
            'task' => new TaskResource($task)
        ]);
    }

    public function update(StoreTaskRequest $request, Project $project, Task $task)
    {
        $project->tasks()->create($request->only(['name','hours']));
        return redirect()->route('teacher.projects.show',['project' => $project]);
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();
        return redirect()->back();
    }
}
