<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Enrollment extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'user_id',
        'course_id',
        'progress', //int 100 pour 100%
        'completed', //boolean
        'reminded_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
