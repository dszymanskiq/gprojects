<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    public function created(Project $project): void
    {
        for($i=0; $i<$project->groups; $i++) {
            $project->groups()->create();
        }
    }
}