<?php

namespace App\Models\Users;

use App\Models\Group;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Student extends User
{
    use HasParent;

    protected $table = 'users';

    public function groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function projects()
    {
        return $this->hasManyThrough(Project::class, Group::class);
    }
}
