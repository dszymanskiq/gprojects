<?php

namespace App\Models\Users;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Teacher extends User
{
    use HasParent;

    protected $table = 'users';

    public function projects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Project::class);
    }
}
