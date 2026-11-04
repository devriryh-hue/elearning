<?php
// app/Application/Course/Commands/CreateCourseHandler.php
namespace App\Application\Course\Commands;

use App\Models\Course;
use Illuminate\Support\Facades\DB;

final class CreateCourseHandler
{
    public function __invoke(CreateCourseCommand $command): int
    {
        return DB::transaction(function () use ($command) {
            $course = Course::create([
                'title'       => $command->title,
                'description' => $command->description,
                'teacher_id'  => $command->teacherId,
            ]);

            if ($command->pdf) {
                $course->addMedia($command->pdf)->toMediaCollection('pdf');
            }

            return $course->id;
        });
    }
}