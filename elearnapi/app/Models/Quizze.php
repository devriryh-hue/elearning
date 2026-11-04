<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Lesson;
use App\Models\Quiz_answer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Quizze extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'lesson_id',
        'question',
        'points',
        'position',
        'next_position',
        'options',
        'note'
    ];
     protected $casts = [
        'options' => 'array',
    ];
     public function quiz_answers()
    {
        return $this->hasMany(Quiz_answer::class, 'quiz_id');
    }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
}
