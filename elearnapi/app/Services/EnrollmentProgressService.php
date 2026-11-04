<?php
// app/Services/EnrollmentProgressService.php
namespace App\Services;

use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Quiz_attempt;

class EnrollmentProgressService
{
    public function recalculateForCourse(int $courseId): void
    {
        $totalLessons = Lesson::where('course_id', $courseId)->count();

        // Leçons distinctes terminées, par utilisateur, en UNE requête
        $doneByUser = Quiz_attempt::query()
            ->where('course_id', $courseId)
            ->where('status', 'completed')
            ->selectRaw('user_id, COUNT(DISTINCT lesson_id) as done')
            ->groupBy('user_id')
            ->pluck('done', 'user_id');

        Enrollment::where('course_id', $courseId)
            ->chunkById(200, function ($enrollments) use ($doneByUser, $totalLessons) {
                foreach ($enrollments as $enrollment) {
                    $done = (int) ($doneByUser[$enrollment->user_id] ?? 0);

                    $progress  = $totalLessons > 0
                        ? (int) min(100, floor($done * 100 / $totalLessons))
                        : 0;
                    $completed = $totalLessons > 0 && $done >= $totalLessons;

                    if ($enrollment->progress !== $progress
                        || (bool) $enrollment->completed !== $completed) {
                        $enrollment->update([
                            'progress'  => $progress,
                            'completed' => $completed,
                        ]);
                    }
                }
            });
    }
}