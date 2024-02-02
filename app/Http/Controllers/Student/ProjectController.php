<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function join(Project $project)
    {
        echo "Dołączono do projektu: $project->name";
        return 1;
    }
}
