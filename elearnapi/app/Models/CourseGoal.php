<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseGoal extends Model
{
    //
    protected $fillable = [
        'goal_id',
        'course_id',
        'required_attempts', // tentatives par leçon
        'target_percent',    // score cible
        'weight',             // importance dans l'objectif
        'created_at',
        'updated_at',
    ];
}
