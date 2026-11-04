<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;


use App\Models\Quiz_answer;
use Illuminate\Http\Request;

class QuizAnswerController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $page = request('page', 1);
$perPage = request('per_page', 5);

$Quizz_answers =Cache::remember(
    "Quizz_answers_page_{$page}_perpage_{$perPage}",
    60,
    fn () => Quiz_answer::with('quiz')
        ->latest()
        ->paginate($perPage)
);
return response()->json($Quizz_answers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'answer' => 'required',
            'is_correct' => 'required',
            'quiz_id' => 'required',
        ]);

        $quiz_answer = Quiz_answer::create([
            'answer' => $validated['answer'],
            'is_correct' => $validated['is_correct'],
            'quiz_id' => $validated['quiz_id'],
        ]);
        return response()->json($quiz_answer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz_answer $quiz_answer)
    {
        //
        return $quiz_answer->load('quiz');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz_answer $quiz_answer)
    {
        //
        $validated = $request->validate([
            'answer' => 'required',
            'is_correct' => 'required',
            'quiz_id' => 'required',
        ]);

        $quiz_answer->update([
            'answer' => $validated['answer'],
            'is_correct' => $validated['is_correct'],
            'quiz_id' => $validated['quiz_id'],
        ]);
        return response()->json($quiz_answer->load('quiz'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz_answer $quiz_answer)
    {
        //
        $quiz_answer->delete();

        return response()->json([
            'message' => 'Quiz answer deleted'
        ]);
    }
}
