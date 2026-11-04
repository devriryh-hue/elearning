<?php

namespace App\Console\Commands;

use App\Models\Enrollment;
use App\Notifications\CourseNotCompletedReminder;
use Illuminate\Console\Command;

class SendCourseReminders extends Command
{
    protected $signature = 'enrollments:remind {--days=7 : Délai minimal entre deux rappels}';
    protected $description = 'Notifie les étudiants dont le cours n\'est pas terminé';

    public function handle(): int
    {
        $threshold = now()->subDays((int) $this->option('days'));

        Enrollment::query()
            ->where('completed', false)
            ->where('created_at', '<=', $threshold) // inscrit depuis au moins N jours
            ->where(function ($q) use ($threshold) {
                $q->whereNull('reminded_at')
                  ->orWhere('reminded_at', '<=', $threshold);
            })
            ->with(['user:id,name,email', 'course:id,title'])
            ->chunkById(200, function ($enrollments) {
                foreach ($enrollments as $enrollment) {
                    if (! $enrollment->user || ! $enrollment->course) {
                        continue;
                    }

                    $enrollment->user->notify(new CourseNotCompletedReminder(
                        courseId: $enrollment->course->id,
                        courseTitle: $enrollment->course->title,
                        progress: (int) $enrollment->progress,
                    ));

                    $enrollment->forceFill(['reminded_at' => now()])->save();
                }
            });

        return self::SUCCESS;
    }
}