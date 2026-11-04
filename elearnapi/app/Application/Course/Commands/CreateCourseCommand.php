<?php
// app/Application/Course/Commands/CreateCourseCommand.php
namespace App\Application\Course\Commands;

use Illuminate\Http\UploadedFile;

final readonly class CreateCourseCommand
{
    public function __construct(
        public string $title,
        public string $description,
        public int $teacherId,
        public ?UploadedFile $pdf = null,
    ) {}
}