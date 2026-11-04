<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz_answers extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     * course_id
quiz_id
answer
is_correct
     */
    protected $fillable = [
        'quiz_id',
        'answer',
        'is_correct',
    ];
    public function quiz()
    {
        return $this->belongsTo(Quizzes::class, 'quiz_id');
    }
}
