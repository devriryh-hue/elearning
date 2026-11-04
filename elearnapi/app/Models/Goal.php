<?php
// app/Models/Goal.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'deadline', 'status', 'completed_at'];

    protected $casts = [
        'deadline'     => 'date',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)
            ->withPivot(['required_attempts', 'target_percent', 'weight'])
            ->withTimestamps();
    }
}