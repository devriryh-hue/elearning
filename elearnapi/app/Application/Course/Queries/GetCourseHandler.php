<?php
// app/Application/Course/Queries/GetCourseHandler.php
namespace App\Application\Course\Queries;

use App\Models\Course;

final class GetCourseHandler
{
    public function __invoke(GetCourseQuery $query): Course
    {
        return Course::with('teacher:id,name')
            ->withCount('lessons')
            ->findOrFail($query->courseId);
    }
}