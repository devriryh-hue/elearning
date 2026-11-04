<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Courses;
class Enrollments extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     * user_id
course_id
progress
completed
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'progress',
        'completed',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(Courses::class);
    }
}
