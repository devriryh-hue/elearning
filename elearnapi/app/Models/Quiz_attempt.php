<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Lesson;
/**
 * champ
 * id
user_id
lesson_id
score
max_score
started_at
finished_at
status
 */
class Quiz_attempt extends Model
{
    //
    protected $fillable = [
        'user_id',
        'lesson_id',
        'score',
        'max_score',
        'started_at',
        'finished_at',
        'status',
        'course_id'
    ];
    protected $casts = [
    'started_at'  => 'datetime',
    'finished_at' => 'datetime',
];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
public function answers()
    {
        return $this->hasMany(Quiz_attempt_answer::class, 'quiz_attempt_id');
    }
    public function calculateScore()
{
    return $this->answers()->sum('points_earned');
}
}
