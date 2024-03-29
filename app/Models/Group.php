<?php

namespace App\Models;

use App\Models\Users\Student;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function students(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }
}
