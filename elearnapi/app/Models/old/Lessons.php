<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Courses;
use App\Models\Quizzes;
use App\Models\Quiz_attempt;
class Lessons extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     * course_id
title
video_url
duration
position
     */
    protected $fillable = [
        'course_id',
        'title',
        'video_url',
        'duration',
        'position'
    ];
        
    public function course()
    {
        return $this->belongsTo(Courses::class);
    }
    public function quizzes()
    {
        return $this->hasMany(Quizzes::class);
    }
    public function quiz_attemps()
    {
        return $this->hasMany(Quiz_attempt::class);
    }
}
