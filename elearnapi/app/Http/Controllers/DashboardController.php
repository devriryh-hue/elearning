<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Quiz_attempt;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course:id,title')
            ->get();

        $attempts = Quiz_attempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('lesson:id,title')
            ->orderBy('finished_at')
            ->get()
            ->groupBy('course_id');

        $courses = $enrollments->map(function ($enrollment) use ($attempts) {
            $list = $attempts->get($enrollment->course_id, collect());

            $history = $list->map(fn ($a) => [
                'id'        => $a->id,
                'lesson'    => $a->lesson?->title,
                'score'     => (int) $a->score,
                'max_score' => (int) $a->max_score,
                'percent'   => $this->percent($a->score, $a->max_score),
                'date'      => $a->finished_at ?? $a->started_at,
            ])->values();

            return [
                'course_id'       => $enrollment->course_id,
                'title'           => $enrollment->course?->title,
                'progress'        => (int) $enrollment->progress,
                'completed'       => (bool) $enrollment->completed,
                'attempts_count'  => $history->count(),
                'lessons_done'    => $list->pluck('lesson_id')->unique()->count(),
                'best_percent'    => $history->max('percent') ?? 0,
                'average_percent' => round($history->avg('percent') ?? 0, 1),
                'last_percent'    => $history->last()['percent'] ?? null,
                'history'         => $history,
            ];
        })->values();

        $all = $courses->flatMap(fn ($c) => $c['history']);

        return response()->json([
            'summary' => [
                'courses_total'     => $courses->count(),
                'courses_completed' => $courses->where('completed', true)->count(),
                'attempts_total'    => $all->count(),
                'average_percent'   => round($all->avg('percent') ?? 0, 1),
            ],
            'courses' => $courses,
        ]);
    }

    private function percent($score, $max): float
    {
        if ((int) $max <= 0) {
            return 0.0;
        }

        // plafonné à 100 pour éviter les cas "40 / 30"
        return min(100.0, round($score * 100 / $max, 1));
    }
}