<?php

namespace App\Http\Controllers;

use App\Models\Quiz_attempt_answer;
use Illuminate\Http\Request;
use App\Models\Quiz_attempt;
use App\Models\Quizze;


class QuizAttemptAnswerController extends Controller
{
/*
    public function save(Request $request, Quiz_attempt $attempt)
    {
        $quiz = Quizze::findOrFail($request->quiz_id);
        $options = $quiz->options;
        $correctAnswer = collect($options)
            ->firstWhere('correct', true);
        $isCorrect = $correctAnswer['text'] === $request->selected_answer;
        $quizze = Quiz_attempt_answer::create([
            'quiz_attempt_id' => $attempt->id,
            'quiz_id' => $quiz->id,
            'selected_answer' => $request->selected_answer,
            'is_correct' => $isCorrect,
            'points_earned' => $isCorrect ? $quiz->points : 0,
            'answered_at' => now(),
        ]);
        return response()->json($quizze->load('quiz_attempt'), 201);
    }*/
        public function save(Request $request, Quiz_attempt $attempt)
{
    $request->validate([
        'quiz_id' => 'required|exists:quizzes,id', // adapte le nom de ta table
        //'selected_answer' => 'required',
        //'selected_answer.*' => 'string',
    ]);
    
    $quiz = Quizze::findOrFail($request->quiz_id);

    // Toutes les bonnes réponses
    $correctAnswers = collect($quiz->options)
        ->where('correct', true)
        ->pluck('text')
        ->values();

    // Toujours un tableau
    $selected = collect(is_array($request->selected_answer)
        ? $request->selected_answer
        : [$request->selected_answer]
    )->values();

    // Correct si les deux ensembles sont identiques
    $isCorrect = $selected->count() === $correctAnswers->count()
        && $selected->diff($correctAnswers)->isEmpty()
        && $correctAnswers->diff($selected)->isEmpty();

    $quizze = Quiz_attempt_answer::create([
        'quiz_attempt_id' => $attempt->id,
        'quiz_id'         => $quiz->id,
        'selected_answer' => $selected->toJson(), // ou $selected->all() si cast 'array' dans le modèle
        'is_correct'      => $isCorrect,
        'points_earned'   => $isCorrect ? $quiz->points : 0,
        'answered_at'     => now(),
    ]);

    return response()->json($quizze->load('quiz_attempt'), 201);
}
    public function details(Quiz_attempt $attempt)
    {
        $answers['quizz'] = $attempt->load('lesson');
        $answers['answers'] = Quiz_attempt_answer::where('quiz_attempt_id', $attempt->id)->with("quiz")->get();
        return response()->json($answers);
    }
}
