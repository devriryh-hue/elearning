<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//pour media
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Quiz_attempt;

class Course extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia; //pour media
    protected $fillable = [
        'id',
        'title',
        'description',
        'teacher_id',
    ];

    protected $appends = [
        'pdf_url'
    ];
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    public function quiz_attempt()
    {
        return $this->hasMany(Quiz_attempt::class);
    }
    /**
     * pour les médias
     */
    protected function pdfUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getFirstMediaUrl('pdf') ?: asset('images/default-avatar.png');
            }
        );
    }
    public function pourcentage()
    {
        $totallesson = $this->lessons()->count();
        $quiztotal = Quiz_attempt::where('course_id', $this->id)
            ->where('status', 'completed')
            ->count();
        $tt = ($quiztotal * 100) / $totallesson;
        if ($tt > 100) {
            $tt = 100;
        }
        return $tt;
    }
    // Course.php
    public function goals()
    {
        return $this->belongsToMany(Goal::class)->withPivot(['required_attempts', 'target_percent', 'weight'])->withTimestamps();
    }
}
