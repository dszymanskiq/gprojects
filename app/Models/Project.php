<?php

namespace App\Models;

use App\Models\Users\Student;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Project extends Model
{

    use HasRelationships;

    protected $fillable = [
        'teacher_id',
        'name',
        'description',
        'groups',
        'closed',
        'due_date',
        'slug'
    ];
    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function students()
    {
        return $this->hasManyDeep(
            Student::class,
            [Group::class, 'group_student'], // Ścieżka relacji: Project -> Group -> group_student (pivot table)
            ['project_id', 'group_id', 'id'], // Foreign keys
            ['id', 'id', 'student_id'] // Local keys
        );
    }
}
