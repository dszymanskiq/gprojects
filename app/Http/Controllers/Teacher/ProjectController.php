<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('dashboard');
    }

    public function create(): Response
    {
        return Inertia::render('Teacher/Project/Create');
    }

    public function store(StoreProjectRequest $request)
    {
        $request->merge([
            'slug' => Str::random(6)
        ]);
        auth()->user()->projects()->create($request->only(['name','description','due_date','groups','slug']));
        return redirect()->route('dashboard')->with('success', 'Projekt został dodany');
    }

    public function show(Project $project): Response
    {
        $projects = new ProjectResource($project->load(['students.groups','tasks.student', 'groups'])->loadCount('students'));
        $projectsCollection = collect([$projects])->map(function($project) {
            $project->students = $project->students->map(function($student) use($project) {
                $student->studentTimerEntries = $student->timerEntries()->whereIn('task_id', $project->tasks->pluck('id'))->get();
                return $student;
            });
            return $project;
        });
        return Inertia::render('Teacher/Project/Show', [
            'project' => $projectsCollection->first()
        ]);
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        return redirect()->route('dashboard')->with('success', 'Projekt został usunięty');
    }
}
