<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Course;
use App\Models\Quizze;

use App\Models\Quiz_attempt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Lesson extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'course_id',
        'title',
        'video_url',
        'duration',
        'position',
        'description'
    ];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function quizzes()
    {
        return $this->hasMany(Quizze::class);
    }
    public function quiz_attemps()
    {
        return $this->hasMany(Quiz_attempt::class);
    }
}
