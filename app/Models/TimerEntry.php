<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_at',
        'end_at',
        'student_id',
        'task_id'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime'
    ];
}
