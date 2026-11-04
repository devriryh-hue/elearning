<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Lessons;
class Courses extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'teacher_id',
    ];
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function lessons()
    {
        return $this->hasMany(Lessons::class);
    }
}
