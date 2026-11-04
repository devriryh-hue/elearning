<?php

namespace App\Http\Controllers;

use App\Application\Bus\CommandBus;
use App\Application\Bus\QueryBus;
use App\Application\Course\Commands\CreateCourseCommand;
use App\Application\Course\Queries\GetCourseQuery;
use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz_attempt;
use App\Models\Quizze;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

//pour la pourcentage enrollments:
use Illuminate\Support\Facades\DB;
use App\Jobs\RecalculateCourseProgress;

class CourseController extends Controller
{
    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus,
    ) {}
    public function getAllCourses()
    {
        $courses = Cache::remember('course_list', 60, function () {
            return Course::all();
        });

        return response()->json($courses);
    }
    /**
     * Display a listing of the resource.
     */
    public function indexOLD()
    {
        //CECI RENVOYE LES MEMES DONNEES POUR LES PAGES SUIVANTES, IL FAUT AJOUTER LA PAGINATION
        $page = request('page', 1);
        $perPage = request('per_page', 5);

        $courses = Cache::remember(
            "courses_page_{$page}_perpage_{$perPage}",
            60,
            fn() => Course::with('teacher')
                ->latest()
                ->paginate($perPage)
        );
        return response()->json($courses);
    }
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 5);
        $search = $request->input('search', '');

        $cacheKey = "courses_page_{$page}_perpage_{$perPage}_search_" . md5($search);

        $courses = Cache::remember($cacheKey, 05, function () use ($search, $perPage) {

            $query = Course::with('teacher')->latest();

            if (!empty($search)) {
                $query->where('title', 'like', "%{$search}%");
            }

            return $query->paginate($perPage);
        });
        return response()->json($courses);
    }
    public function avecLessons()
    {

        $page = request('page', 1);
        $perPage = request('per_page', 6);
        $search = request('search', '');

        $cacheKey = "courseswithlessons_page_{$page}_perpage_{$perPage}_search_" . md5($search);

        $courses = Cache::remember($cacheKey, 05, function () use ($search, $perPage) {

            //$query = Course::with('teacher', 'lessons')->latest();
            $query = Course::with('teacher', 'lessons')
                ->whereDoesntHave('quiz_attempt')
                ->orWhereHas('quiz_attempt', fn($quiz) => $quiz->where('status', 'in_progress'))
                ->latest();
            if (!empty($search)) {
                $query->where('title', 'like', "%{$search}%");
            }

            return $query->paginate($perPage);
        });
        return response()->json($courses);
    }
    /**
     * Store a newly created resource in storage. ARCHITECTURE CQRS
     */
    

    public function store(StoreCourseRequest $request)
    {
        $courseId = $this->commandBus->dispatch(new CreateCourseCommand(
            title: $request->validated('title'),
            description: $request->validated('description'),
            teacherId: (int) $request->validated('teacher_id'),
            pdf: $request->file('pdf'),
        ));

        $course = $this->queryBus->ask(new GetCourseQuery($courseId));

        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
        return $course->load('teacher');
    }


    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:users,id',
            //'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $course->update($validated);

        return response()->json($course);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
        $course->delete();

        return response()->json([
            'message' => 'Cours supprimé'
        ]);
    }
    public function showAllLessons(Course $course)
    {
        
        //
        $page = request('page', 1);
        $perPage = request('per_page', 5);
        $coursid = $course->id;
        $lessons = Cache::remember(
            "lessons_page_{$page}_perpage_{$perPage}_cours_{$coursid}",
            60,
            fn() => $course->lessons()
                ->latest()
                ->paginate($perPage)
        );
        return response()->json($lessons);
    }
}
