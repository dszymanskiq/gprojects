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
            $groupToJoin = $project->groups()->skip(($studentsCount%$project->groups_count))->first();
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
        $projects = new ProjectResource($project->load(['students.groups','tasks.students', 'groups'])->loadCount('students'));
        $projectsCollection = collect([$projects])->map(function($project) {
            $project->students = $project->students->map(function($student) use($project) {
                $student->timerEntries = $student->timerEntries()->whereHas('task', function ($query) use ($project) {
                    $query->where('project_id', $project->id);
                })->get();
                return $student;
            });
            $project->tasks = $project->tasks->map(function($task) {
                $task->timerEntries = $task->timerEntries()->get();
                return $task;
            });
            return $project;
        })->toArray();
        return Inertia::render('Student/Projects/Show', [
            'group' => auth()->user()->groups()->where('project_id', $project->id)->first(),
            'project' => $projectsCollection[0]
        ]);
    }
}
