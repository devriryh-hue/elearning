<?php
// app/Services/GoalProgressService.php
namespace App\Services;

use App\Models\Goal;
use App\Models\Quiz_attempt;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GoalProgressService
{
    private const RECENT = 3;
    private const STALE_DAYS = 30;

    public function forGoal(Goal $goal, int $evolutionWeeks = 0): array
    {
        $goal->load('courses.lessons:id,course_id,title');

        $lessonIds = $goal->courses->flatMap(fn ($c) => $c->lessons)->pluck('id');

        // Une seule requête : toutes les tentatives terminées, avec une date fiable
        $attempts = Quiz_attempt::where('user_id', $goal->user_id)
            ->where('status', 'completed')
            ->whereIn('lesson_id', $lessonIds)
            ->get()
            ->map(function ($a) {
                $a->setAttribute('done_at', Carbon::parse($a->finished_at ?? $a->started_at ?? $a->created_at));
                return $a;
            })
            ->sortBy('done_at')
            ->groupBy('lesson_id');

        $result = $this->compute($goal, $attempts, now());

        if ($evolutionWeeks > 0) {
            $result['evolution'] = collect(range($evolutionWeeks, 0))->map(function ($i) use ($goal, $attempts) {
                $date = now()->subWeeks($i)->endOfDay();

                return [
                    'date'     => $date->toDateString(),
                    'progress' => $this->compute($goal, $attempts, $date)['progress'],
                ];
            })->all();
        }

        return $result;
    }

    private function compute(Goal $goal, Collection $attempts, Carbon $asOf): array
    {
        $courses = $goal->courses->map(function ($course) use ($attempts, $asOf) {
            $required = $course->pivot->required_attempts;
            $target   = max(1, $course->pivot->target_percent);
            //$course_id = $course->id;
            $lessons = $course->lessons->map(function ($lesson) use ($attempts, $asOf, $required, $target) {
                // Seulement les tentatives faites avant la date étudiée
                $list = $attempts->get($lesson->id, collect())
                    ->filter(fn ($a) => $a->done_at->lte($asOf))
                    ->values();

                $count   = $list->count();
                $percent = $list->map(fn ($a) => $a->max_score > 0
                    ? min(100, $a->score * 100 / $a->max_score) : 0);
                $recent  = $percent->slice(-self::RECENT)->avg() ?? 0;
                $lastAt  = $list->last()?->done_at;

                $progress = round(min(1, $count / $required) * min(1, $recent / $target) * 100);

                $status = match (true) {
                    $count === 0                   => 'not_started',
                    $count < $required             => 'learning',
                    $recent < $target              => 'to_review',
                    $lastAt && $lastAt->lt($asOf->copy()->subDays(self::STALE_DAYS)) => 'stale',
                    default                        => 'mastered',
                };

                return [
                    'course_id'  => $lesson->course_id,
                    'lesson_id'  => $lesson->id,
                    'title'      => $lesson->title,
                    'attempts'   => $count,
                    'required'   => $required,
                    'recent_avg' => round($recent, 1),
                    'target'     => $target,
                    'progress'   => $progress,
                    'status'     => $status,
                ];
            })->values();

            return [
                //'course_id' => $course->id,
                'title'     => $course->title,
                'weight'    => $course->pivot->weight,
                'attempts'  => $lessons->sum('attempts'),
                'required'  => $lessons->count() * $required,
                'progress'  => round($lessons->avg('progress') ?? 0),
                'lessons'   => $lessons,
            ];
        })->values();

        $totalWeight = max(1, $courses->sum('weight'));

        return [
            'goal'     => $goal->only(['id', 'title', 'description', 'deadline', 'status']),
            'progress' => round($courses->sum(fn ($c) => $c['progress'] * $c['weight']) / $totalWeight),
            'courses'  => $courses,
            'weak'     => $courses->flatMap(fn ($c) => $c['lessons']->map(
                                fn ($l) => $l + ['course' => $c['title']]))
                              ->where('status', '!=', 'mastered')
                              ->sortBy('progress')->take(5)->values(),
        ];
    }
}