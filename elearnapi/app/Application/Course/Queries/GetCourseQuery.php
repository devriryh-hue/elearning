<?php
// app/Application/Course/Queries/GetCourseQuery.php
namespace App\Application\Course\Queries;

final readonly class GetCourseQuery
{
    public function __construct(public int $courseId) {}
}