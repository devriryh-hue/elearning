<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Services\GoalProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoalController extends Controller
{
    public function __construct(private GoalProgressService $progress) {}

    public function index(Request $request)
    {
        $goals = $request->user()->goals()->latest()->get()->map(function (Goal $goal) {
            $data = $this->progress->forGoal($goal);

            return [
                'id'        => $goal->id,
                'title'     => $goal->title,
                'deadline'  => $goal->deadline?->toDateString(),
                'days_left' => $this->daysLeft($goal),
                'progress'  => $data['progress'],
                'mastered'  => $data['weak']->isEmpty(),
            ];
        });

        return response()->json($goals);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'                       => ['required', 'string', 'max:255'],
            'description'                 => ['nullable', 'string'],
            'deadline'                    => ['nullable', 'date', 'after_or_equal:today'],
            'courses'                     => ['required', 'array', 'min:1'],
            'courses.*.course_id'         => ['required', 'integer', 'exists:courses,id', 'distinct'],
            'courses.*.required_attempts' => ['required', 'integer', 'between:1,20'],
            'courses.*.target_percent'    => ['required', 'integer', 'between:1,100'],
            'courses.*.weight'            => ['required', 'integer', 'between:1,10'],
        ]);

        $goal = DB::transaction(function () use ($request, $data) {
            $goal = $request->user()->goals()->create([
                'title'       => $data['title'],
                'description' => $data['description'] ?? null,
                'deadline'    => $data['deadline'] ?? null,
            ]);

            $goal->courses()->sync(
                collect($data['courses'])->mapWithKeys(fn($c) => [
                    $c['course_id'] => [
                        'required_attempts' => $c['required_attempts'],
                        'target_percent'    => $c['target_percent'],
                        'weight'            => $c['weight'],
                    ],
                ])->all()
            );

            return $goal;
        });

        return response()->json(['id' => $goal->id], 201);
    }

    public function show(Request $request, int $id)
    {
        // findOrFail sur la relation : un autre utilisateur obtient un 404
        $goal = $request->user()->goals()->findOrFail($id);
        $data = $this->progress->forGoal($goal);

        return response()->json($data + [
            'days_left' => $this->daysLeft($goal),
            'mastered'  => $data['weak']->isEmpty(),
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        $request->user()->goals()->findOrFail($id)->delete();

        return response()->noContent();
    }

    private function daysLeft(Goal $goal): ?int
    {
        return $goal->deadline
            ? (int) now()->startOfDay()->diffInDays($goal->deadline, false)
            : null;
    }
    public function overview(Request $request)
    {
        $goals = $request->user()->goals()
            ->where('status', '!=', 'archived')
            ->get();

        $items = $goals->map(function (Goal $goal) {
            $d         = $this->progress->forGoal($goal, 8);
            $evolution = collect($d['evolution']);
            $weekAgo   = $evolution->get($evolution->count() - 2)['progress'] ?? $d['progress'];

            return [
                'id'        => $goal->id,
                'title'     => $goal->title,
                'deadline'  => $goal->deadline?->toDateString(),
                'days_left' => $this->daysLeft($goal),
                'progress'  => $d['progress'],
                'trend'     => $d['progress'] - $weekAgo,   // points gagnés ou perdus en 7 jours
                'mastered'  => $d['weak']->isEmpty(),
                'weak'      => $d['weak'],
                'courses'   => $d['courses'],
                'evolution' => $d['evolution'],
                //'course_id'  => $goal->course_id,
            ];
        });

        // "À faire aujourd'hui" : leçons non maîtrisées, objectifs les plus urgents d'abord
        $today = $items
            ->flatMap(fn($g) => $g['weak']->map(fn($l) => $l + [
                'goal'      => $g['title'],
                'goal_id'   => $g['id'],
                'days_left' => $g['days_left'],
                //'goal_data'=> $g,
            ]))
            ->unique('lesson_id')
            ->sortBy([
                fn($a, $b) => ($a['days_left'] ?? PHP_INT_MAX) <=> ($b['days_left'] ?? PHP_INT_MAX),
                fn($a, $b) => $a['progress'] <=> $b['progress'],
            ])
            ->take(5)
            ->values();

        return response()->json([
            'summary' => [
                'active'           => $items->count(),
                'average_progress' => round($items->avg('progress') ?? 0),
                'late'             => $items->filter(fn($g) => $g['days_left'] !== null
                    && $g['days_left'] < 0 && ! $g['mastered'])->count(),
                'mastered'         => $items->where('mastered', true)->count(),
            ],
            'today' => $today,
            'goals' => $items->values(),
        ]);
    }
}
