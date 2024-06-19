<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function join(Project $project)
    {
        $projectGroupsIds = $project->groups()->pluck('id')->toArray();
        $isStudentInAnyProjectGroup = auth()->user()->groups()->whereIn('groups.id', $projectGroupsIds)->exists();
        if(!$isStudentInAnyProjectGroup) {
            $studentsCount = $project->students()->count();
            $groupToJoin = $project->groups()->skip($studentsCount/$project->groups)->first();
            auth()->user()->groups()->attach($groupToJoin);
        }
        return redirect()->route('student.dashboard');
    }
}
