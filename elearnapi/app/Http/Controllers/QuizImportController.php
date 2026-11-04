<?php

namespace App\Http\Controllers;

use App\Services\QuizImportService;
use Illuminate\Http\Request;

class QuizImportController extends Controller
{
    public function __construct(
        protected QuizImportService $quizImportService
    ) {}

    public function uploadJson(Request $request)
    {
        $validated = $request->validate([
            'json_file'    => ['required', 'file'],
            'lesson_title' => ['required', 'string'],
        ]);

        $course = $this->quizImportService->import(
            $request->file('json_file'),
            $validated + [
                'course_id'  => $request->input('course_id'),
                'cours_title' => $request->input('cours_title'),
            ],
            'choices'
        );

        return response()->json([
            'success' => true,
            'message' => 'Fichier JSON téléchargé avec succès',
            'course'  => $course,
        ]);
    }

    public function uploadJsonFormatBD(Request $request)
    {
        $validated = $request->validate([
            'json_file'    => ['required', 'file'],
            'lesson_title' => ['required', 'string'],
        ]);

        $course = $this->quizImportService->import(
            $request->file('json_file'),
            $validated + [
                'course_id'  => $request->input('course_id'),
                'cours_title' => $request->input('cours_title'),
            ],
            'options'
        );

        return response()->json([
            'success' => true,
            'message' => 'Fichier JSON téléchargé avec succès',
        ]);
    }
}
