<?php

namespace App\Models\AccountTypes;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Student extends Model
{
    use HasParent;

    public function projects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Project::class);
    }
}
