<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Quizze;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Quiz_answer extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'quiz_id',
        'answer',
        'is_correct',
    ];
    public function quiz()
    {
        return $this->belongsTo(Quizze::class, 'quiz_id');
    }
}
