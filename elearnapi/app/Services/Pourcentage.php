<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Quiz_attempt;
use App\Models\Course;
class Pourcentage{
    public function __construct(
        
    )
    {
    }
    public function pourcentage(Course $course)
    {
        $totallesson = $course->lessons()->count();
        $quiztotal = Quiz_attempt::where('course_id', $course->id)
            ->where('status', 'completed')
            ->get();
            $allidlessons = array();
            foreach ($quiztotal as $key => $value) {
                $allidlessons[] = $value->lesson_id;
            }
            $allidlessons = array_unique($allidlessons);
            $allquiztotal = count($allidlessons);
        $tt = ($allquiztotal * 100) / $totallesson;
        if($tt>100){
            $tt = 100;
        }
        return $tt;
    }
}