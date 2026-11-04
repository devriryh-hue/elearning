<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lessons;
use App\Models\Quiz_answers;
class Quizzes extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     * 
lesson_id
question
     */
    protected $fillable = [
        'lesson_id',
        'question'
    ];
     
     public function quiz_answers()
    {
        return $this->hasMany(Quiz_answers::class, 'quiz_id');
    }
    public function lessons()
    {
        return $this->belongsTo(Lessons::class, 'lesson_id');
    }
}
