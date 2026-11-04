<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizAnswerController;
use App\Http\Controllers\QuizAttemptAnswerController;
use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\QuizGenerationController;
use App\Http\Controllers\QuizImportController;
use App\Http\Controllers\QuizzeController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return ['message' => 'API OK'];
});

Route::post('/register', function (Request $request) {

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'student'
    ]);

    return response()->json([
        'message' => 'Utilisateur créé',
        'user' => $user
    ]);
});
Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (! Auth::attempt($credentials)) {

        return response()->json([
            'message' => 'Identifiants invalides'
        ], 401);
    }

    $user = Auth::user();

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
});
/** */
Route::get('/allUsers', [UserController::class, 'getAllUsers']);
Route::get('/allCourses', [CourseController::class, 'getAllCourses']);
Route::get('/allLessons', [LessonController::class, 'getAllLessons']);
Route::get('/allQuizzes', [QuizzeController::class, 'getAllQuizz']);

/**api */
Route::apiResource('courses', CourseController::class);
Route::apiResource('enrollments', EnrollmentController::class);
Route::apiResource('lessons', LessonController::class);
Route::apiResource('quizzes', QuizzeController::class);
Route::apiResource('quiz-answers', QuizAnswerController::class);
Route::apiResource('quiz-attempt-answers', QuizAttemptAnswerController::class);
Route::apiResource('quiz-attempts', QuizAttemptController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('users', UserController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('goals', GoalController::class)
        ->only(['index', 'store', 'show', 'destroy']);
    Route::get('/dashboard/goals', [GoalController::class, 'overview']);
});
Route::get('/lessons/{lesson}/quizzes/{position}', [QuizzeController::class, 'showByPosition']);
Route::get(
    '/attempts/{quiz_attempt}',
    [QuizAttemptController::class, 'show']
);
Route::get(
    '/lessons/{lesson}/allquizzes',
    [LessonController::class, 'showAllQuizzes']
);
Route::get(
    '/cours/{course}/alllessons',
    [CourseController::class, 'showAllLessons']
);

/**réponse */
Route::middleware('auth:sanctum')->group(function () {

    Route::post(
        '/lessons/{lesson}/attempts',
        [QuizAttemptController::class, 'start']
    );

    Route::post(
        '/attempts/{attempt}/answers',
        [QuizAttemptAnswerController::class, 'save']
    );

    Route::post(
        '/attempts/{attempt}/finish',
        [QuizAttemptController::class, 'finish']
    );

    Route::get(
        '/cours/{course}/competence',
        [EnrollmentController::class, 'competence']
    );
});
Route::get("evaluation/{user}", [EnrollmentController::class, 'evaluation']);
Route::get(
    '/withlessons',
    [CourseController::class, 'avecLessons']
);
Route::get(
    '/attempts/{attempt}/details',
    [QuizAttemptAnswerController::class, 'details']
);
/*
Route::post(
    '/uploadjson',
    [CourseController::class, 'uploadJson']
);
Route::post(
    '/uploadjsonformatbd',
    [CourseController::class, 'uploadJsonFormatBD']
);
*/
Route::post(
    '/uploadjson',
    [QuizImportController::class, 'uploadJson']
);
Route::post(
    '/uploadjsonformatbd',
    [QuizImportController::class, 'uploadJsonFormatBD']
);
Route::post('/quizzes/generate', [QuizGenerationController::class, 'generate'])
    ->middleware(['auth:sanctum', 'throttle:10,1']);

Route::get(
    '/enrollments/{course}/pourcentage',
    [EnrollmentController::class, 'pourcentage']
);

//Queue et notification
// routes/api.php (je suppose Sanctum, adapte le guard si besoin)
Route::middleware('auth:sanctum')->group(function () {
    Route::get(
        '/notifications',
        fn(Request $request) =>
        $request->user()->unreadNotifications()->latest()->limit(20)->get()
    );

    Route::post('/notifications/{id}/read', function (Request $request, string $id) {
        $request->user()->notifications()->whereKey($id)->firstOrFail()->markAsRead();
        return response()->noContent();
    });
});
//dashboard
// routes/api.php
Route::middleware('auth:sanctum')->get('/dashboard', DashboardController::class);
