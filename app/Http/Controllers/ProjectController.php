<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProjectController extends Controller
{
    public function join(Project $project)
    {
        Session::put('project_id', $project->id);
        return redirect()->route('register');
    }
}
