<?php

namespace App\Http\Controllers;

use App\Services\QuizGenerator;
use Illuminate\Http\Request;
use RuntimeException;

class QuizGenerationController extends Controller
{
    public function generate(Request $request, QuizGenerator $generator)
    {
        $data = $request->validate([
            'course_text' => ['required', 'string', 'min:200', 'max:20000'],
            'count' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        try {
            $questions = $generator->generate($data['course_text'], $data['count'] ?? 5);
        } catch (RuntimeException $e) {
            report($e); // détail dans les logs, pas côté client
            return response()->json([
                'success' => false,
                'message' => 'Génération impossible pour le moment, réessayez.',
            ], 502);
        }

        // Rien n'est enregistré ici : c'est un brouillon que l'enseignant valide.
        return response()->json([
            'success' => true,
            'questions' => $questions,
        ]);
    }
}
