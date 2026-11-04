
<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quizze;
use App\Jobs\RecalculateCourseProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class QuizImportService
{
    /**
     * Importer les quiz depuis un fichier JSON.
     *
     * @param UploadedFile $file
     * @param array $data
     * @param string $format
     * @return Course
     */
    public function import(
        UploadedFile $file,
        array $data,
        string $format = 'choices'
    ): Course {

        $json = json_decode(file_get_contents($file));

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($json)) {
            throw ValidationException::withMessages([
                'json_file' => 'Erreur dans le fichier JSON.',
            ]);
        }

        $course = null;

        if (! empty($data['course_id'])) {
            $course = Course::find($data['course_id']);

            if (! $course) {
                throw ValidationException::withMessages([
                    'course_id' => 'Cours introuvable.',
                ]);
            }
        }

        return DB::transaction(function () use ($data, $course, $json, $format) {

            // Création du cours si nécessaire
            $course ??= Course::create([
                'title'      => $data['cours_title'],
                'teacher_id' => 1,
            ]);

            // Création de la leçon
            $lesson = Lesson::create([
                'title'     => $data['lesson_title'],
                'course_id' => $course->id,
            ]);

            $total = count($json);

            foreach ($json as $index => $item) {

                $position = $index + 1;

                Quizze::create([
                    'question'      => $item->question,
                    'options'       => $format === 'choices'
                        ? $item->choices
                        : $item->options,
                    'lesson_id'     => $lesson->id,
                    'position'      => $position,
                    'points'        => 3,
                    'next_position' => $position === $total
                        ? 0
                        : $position + 1,
                    'note'          => $format === 'options'
                        ? ($item->note ?? '')
                        : '',
                ]);
            }

            // Exécution après validation de la transaction
            RecalculateCourseProgress::dispatch($course->id)->afterCommit();

            return $course;
        });
    }
}
