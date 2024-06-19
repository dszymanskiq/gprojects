<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function join(Project $project): Response | RedirectResponse
    {
        if(auth()->user()?->type === 'student') {
            return redirect()->route('student.projects.join', $project);
        }
        Session::put('project_id', $project->id);
        return Inertia::render('Auth/Register', [
            'project' => $project
        ]);
    }
}
