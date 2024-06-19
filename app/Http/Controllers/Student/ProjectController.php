<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function join(Project $project): RedirectResponse
    {
        $projectGroupsIds = $project->groups()->pluck('id')->toArray();
        $isStudentInAnyProjectGroup = auth()->user()->groups()->whereIn('groups.id', $projectGroupsIds)->exists();
        if(!$isStudentInAnyProjectGroup) {
            $studentsCount = $project->students()->count();
            $groupToJoin = $project->groups()->skip($studentsCount/$project->groups)->first();
            auth()->user()->groups()->attach($groupToJoin);
            return redirect()->route('student.dashboard')->with('success', 'Dołączyłeś do projektu '. $project->name);
        }
        return redirect()->route('student.dashboard')->with('info', 'Dołączyłeś już wcześniej do tego projektu');
    }

    public function leave(Project $project): RedirectResponse
    {
        auth()->user()->groups()->detach($project->groups()->pluck('id')->toArray());
        return redirect()->route('student.dashboard')->with('success', 'Opuściłeś projekt '. $project->name);
    }

    public function show(Project $project): Response
    {
        return Inertia::render('Student/Projects/Show', [
            'project' => new ProjectResource($project->load(['students.groups','tasks.student', 'groups'])->loadCount('students'))
        ]);
    }
}
