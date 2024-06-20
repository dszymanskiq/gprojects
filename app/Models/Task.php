<?php

namespace App\Models;

use App\Models\Users\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'id',
        'name',
        'hours',
        'student_id'
    ];

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function timerEntries(): HasMany
    {
        return $this->hasMany(TimerEntry::class);
    }

    protected function currentStudentRegisteredTime(): Attribute
    {
        $result = 0;
        foreach($this->timerEntries as $timerEntry) {
            if($timerEntry->end_at) {
                $result += $timerEntry->start_at->diffInSeconds($timerEntry->end_at);
            } else {
                $result += $timerEntry->start_at->diffInSeconds(now());
            }
        }
        return Attribute::make(
            get: static function () use ($result) {
                return $result;
            }
        );
    }
}
