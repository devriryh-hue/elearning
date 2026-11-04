<?php

namespace App\Http\Controllers;

use App\Models\Quiz_attempt;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class QuizAttemptController extends Controller
{
    /**REPONSE UTILISATEUR */
    public function start(Lesson $lesson){
        //return response()->json($lesson);
        $attempt = Quiz_attempt::create([
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
            'score' => 0,
            'max_score' => $lesson->quizzes()->sum('points'),
            'status' => 'in_progress',
            'started_at' => now(),
            'course_id'=>$lesson->course_id,
        ]);
        return response()->json($attempt, 201);
    }
    public function finish(Request $request, Quiz_attempt $attempt)
{
    
        $attempt->update([
            'user_id' => auth()->id(),
            'lesson_id' => $attempt->lesson_id,
            'course_id'=>$attempt->course_id,
            'score' => $attempt->calculateScore(),
            'max_score' => $attempt->max_score,
            'status' => 'completed',
            'finished_at' => now(),
        ]);
        return response()->json($attempt->load('lesson'), 201);
}
/**FIN REPONSE */
    public function getAllQuizz()
    {
        $quizzes = Cache::remember('quizAttempt_list', 60, function () {
            return Quiz_attempt::all();
        });

        return response()->json($quizzes);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $page = request('page', 1);
        $perPage = request('per_page', 5);

        $Quizzes = Cache::remember(
            "QuizAttempt_page_{$page}_perpage_{$perPage}",
            60,
            fn() => Quiz_attempt::with('lesson')
                ->latest()
                ->paginate($perPage)
        );
        return response()->json($Quizzes);
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        //
        //
        $validated = $request->validate([
            'user_id' => 'required',
            'lesson_id' => 'required',
            'course_id'=>'required',
            'score' => 'required',
            'max_score' => 'required',
        ]);

        $quizze = Quiz_attempt::create([
            'user_id' => $validated['user_id'],
            'lesson_id' => $validated['lesson_id'],
            'course_id'=>$validated['course_id'],
            'score' => $validated['score'],
            'max_score' => $validated['max_score'],
        ]);
        return response()->json($quizze->load('lesson'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz_attempt $quiz_attempt)
    {
        //
        return response()->json($quiz_attempt);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz_attempt $quiz_attempt)
    {
        //
        $validated = $request->validate([
            'user_id' => 'required',
            'lesson_id' => 'required',
            'course_id'=>'required',
            'score' => 'required',
            'max_score' => 'required',
        ]);

        $quiz_attempt->update([
            'user_id' => $validated['user_id'],
            'lesson_id' => $validated['lesson_id'],
            'course_id'=>$validated['course_id'],
            'score' => $validated['score'],
            'max_score' => $validated['max_score'],
        ]);
        return response()->json($quiz_attempt->load('lesson'), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz_attempt $quiz_attempt)
    {
        //
        $quiz_attempt->delete();

        return response()->json([
            'message' => 'Quiz supprimé'
        ]);
    }
}
