<?php

namespace App\Jobs;

use App\Services\EnrollmentProgressService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecalculateCourseProgress implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $courseId) {}

    public function handle(EnrollmentProgressService $service): void
    {
        $service->recalculateForCourse($this->courseId);
    }
}