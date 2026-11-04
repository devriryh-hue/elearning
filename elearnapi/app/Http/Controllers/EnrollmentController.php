<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

use App\Services\Pourcentage as ServicePourcentage;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        //
    }
    public function competence(Course $course, ServicePourcentage $pourcent)
    {
        //id	user_id	course_id	progress	completed	created_at	updated_at	
        $pourcentage = $pourcent->pourcentage($course);
        $array_enrollments = [
            'user_id' => auth()->id(),
            'course_id' => $course->id,
            'progress' => $pourcentage,
            'completed' => ($pourcentage == 100) ? 1 : 0,
        ];
        $verif = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->get();
        if (count($verif) > 0) {
            $enrollment = Enrollment::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->update($array_enrollments);
        } else {
            $enrollment = Enrollment::create($array_enrollments);
        }
        return response()->json($enrollment);
    }
    public function pourcentage(Course $course, ServicePourcentage $pourcentage){
        $pourcent = $pourcentage->pourcentage($course);
        return response()->json($pourcent); 
    }

    public function evaluation(User $user)
    {
        
        $enrollments = $user->enrollments()
            ->with('course.lessons.quiz_attemps', fn($q) => $q->where('status', 'completed'))
            ->get();
        return response()->json($enrollments);
    }
}
