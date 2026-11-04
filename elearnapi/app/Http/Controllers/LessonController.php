<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\Quizze;

class LessonController extends Controller
{

    public function getAllLessons()
    {
        $lessons = Cache::remember('lesson_list', 60, function () {
            return Lesson::all();
        });

        return response()->json($lessons);
    }
    /**
     * Display a listing of the resource.
     */
    public function indexOLD()
    {
        //
        $page = request('page', 1);
        $perPage = request('per_page', 5);
        $lessons = Cache::remember(
            "lessons_page_{$page}_perpage_{$perPage}",
            60,
            fn() => Lesson::with('course')
                ->latest()
                ->paginate($perPage)
        );
        return response()->json($lessons);
    }
public function indexOLD2(Request $request)
{
    $page = $request->input('page', 1);
    $perPage = $request->input('per_page', 5);
    $search = $request->input('search', '');

    $cacheKey = "lessons_page_{$page}_perpage_{$perPage}_search_" . md5($search);

    $lessons = Cache::remember($cacheKey, 60, function () use ($search, $perPage) {

        $query = Lesson::with('course')->latest();

        if (!empty($search)) {
            $query->where('title', 'like', "%{$search}%");
        }

        return $query->paginate($perPage);
    });

    return response()->json($lessons);
}
public function index(Request $request)
{
    $page = $request->input('page', 1);
    $perPage = $request->input('per_page', 5);
    $perPage =  (int) $perPage > 0 ? (int) $perPage : 5; // Ensure perPage is a positive integer
    $search = $request->input('search', '');
    $courseId = $request->input('course_id', '');

    $cacheKey = "lessons_page_{$page}_perpage_{$perPage}_search_" . md5($search) . "_course_" . md5($courseId);

    $lessons = Cache::remember($cacheKey, 05, function () use ($search, $perPage, $courseId) {

        $query = Lesson::with('course')->latest();

        if (!empty($search)) {
            $query->where('title', 'like', "%{$search}%");
        }

        if (!empty($courseId)) {
            $query->where('course_id', $courseId);
        }

        return $query->paginate($perPage);
    });
    /*
$lessons = Lesson::with('course')->latest();

        if (!empty($search)) {
            $lessons->where('title', 'like', "%{$search}%");
        }

        if (!empty($courseId)) {
            $lessons->where('course_id', $courseId);
        }

         $lessons->paginate($perPage);*/
    return response()->json($lessons);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //
        $validated = $request->validate([
            'title' => 'required',
            //'video_url' => 'required',
            'course_id' => 'required|exists:courses,id',
        ]);

        $lesson = Lesson::create([
            'title' => $validated['title'],
            'video_url' => $request->input('video_url'),
            'description' => $request->input('description'),
            'course_id' => $validated['course_id']
        ]);
        /*
//Ajout du PDF
        if ($request->hasFile('pdf')) {
            $course->addMediaFromRequest('pdf')
                ->toMediaCollection('pdf');
        }*/
        return response()->json($lesson, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        //
        return $lesson->load('course');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        //
        $validated = $request->validate([
            'title' => 'required',
            //'video_url' => 'required',
            'course_id' => 'required|exists:courses,id',
        ]);

        $lesson->update([
            'title' => $validated['title'],
            'video_url' => $request->input('video_url'),
            'description' => $request->input('description'),
            'course_id' => $validated['course_id']
        ]);

        return response()->json($lesson);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        //
        $lesson->delete();

        return response()->json([
            'message' => 'Cours supprimé'
        ]);
    }

    public function showAllQuizzes(Lesson $lesson)
    {
        $quizzes = $lesson->load('quizzes');
        return response()->json($quizzes);
    }
}
