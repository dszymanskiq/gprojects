<?php

namespace App\Models\Users;

use App\Models\Group;
use App\Models\Project;
use App\Models\User;
use Parental\HasParent;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Student extends User
{
    use HasParent, HasRelationships;

    protected $table = 'users';

    public function groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_student','student_id');
    }

    public function projects()
    {
        return $this->hasManyDeep(
            Project::class,
            ['group_student', Group::class],
            [
                'student_id',
                'id',
                'id'
            ],
            [
                'id',
                'group_id',
                'project_id'
            ]
        );
    }
}
