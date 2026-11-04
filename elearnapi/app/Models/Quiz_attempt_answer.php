<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Quizze;
use App\Models\Quiz_attempt;
/**
 * champ
 * id
quiz_attempt_id
quiz_id
selected_answer
is_correct
points_earned
answered_at
 */
class Quiz_attempt_answer extends Model
{
    //
    protected $fillable = [
        'quiz_attempt_id',
        'quiz_id',
        'selected_answer',
        'is_correct',
        'points_earned',
        'answered_at',
    ];
    public function quiz_attempt()
    {
        return $this->belongsTo(Quiz_attempt::class, 'quiz_attempt_id');
    }
    public function quiz()
    {
        return $this->belongsTo(Quizze::class, 'quiz_id');
    }
}
