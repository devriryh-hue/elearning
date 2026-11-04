<?php

namespace App\Http\Controllers;

use App\Models\Quizze;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class QuizzeController extends Controller
{
    public function showByPosition(Lesson $lesson, int $position)
    {
        $quiz = $lesson->quizzes()
            ->where('position', $position)
            ->firstOrFail();

        return response()->json($quiz);
    }
    public function getAllQuizz()
    {
        $quizzes = Cache::remember('quiz_list', 60, function () {
            return Quizze::all();
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
            "Quizzes_page_{$page}_perpage_{$perPage}",
            60,
            fn() => Quizze::with('lesson')
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
        $validated = $request->validate([
            'question' => 'required',
            //'options' => 'required',
            'lesson_id' => 'required',
            'position' => 'required',
        ]);

        $quizze = Quizze::create([
            'question' => $validated['question'],
            'options' => $request->input('options'),
            'lesson_id' => $validated['lesson_id'],
            'position' => $validated['position'],
            'points' => $request->input('points'),
            'next_position' => $request->input('next_position'),
            'note' => $request->input('note'),
        ]);
        return response()->json($quizze, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Quizze $quizze)
    {
        //
        /*dd(
        $request->route()->parameters(),
        $request->route('quizze'),
        $quizze
    );*/
        return $quizze->load('lesson');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quizze $quizze)
    {
        //
        $validated = $request->validate([
            'question' => 'required',
            'options' => 'required',
            'lesson_id' => 'required',
            'position' => 'required',
        ]);

        $quizze->update([
            'question' => $validated['question'],
            'options' => $validated['options'],
            'lesson_id' => $validated['lesson_id'],
            'position' => $validated['position'],
            'points' => $request->input('points'),
            'next_position' => $request->input('next_position'),
            'note' => $request->input('note'),
        ]);
        return response()->json($quizze->load('lesson'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quizze $quizze)
    {
        //
        $quizze->delete();

        return response()->json([
            'message' => 'Quiz supprimé'
        ]);
    }
}
