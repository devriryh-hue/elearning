<?php

namespace App\Services;

use App\Models\Quiz_attempt;
use App\Models\Quiz_attempt_answer;
use App\Models\Quizze;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class QuizService
{
    public function startAttempt(User $user, Lesson $lesson)
    {
        $attempt = Quiz_attempt::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'score' => 0,
            'max_score' => $lesson->quizzes()->sum('points'),
            'status' => 'in_progress',
        ]);

        return $attempt;
    }

}
