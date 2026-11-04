<?php

namespace Tests\Feature;

use App\Services\QuizGenerator;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class QuizGeneratorTest extends TestCase
{
    private function fakeApi(array $input, int $status = 200): void
    {
        Http::fake([
            'api.anthropic.com/*' => Http::response([
                'content' => [[
                    'type' => 'tool_use',
                    'name' => 'save_questions',
                    'input' => $input,
                ]],
            ], $status),
        ]);
    }

    public function test_retourne_les_questions_valides(): void
    {
        config(['services.anthropic.key' => 'test-key']);
        $this->fakeApi(['questions' => [[
            'question' => 'Quelle est la capitale de Madagascar ?',
            'choices' => ['Toamasina', 'Antananarivo', 'Mahajanga', 'Toliara'],
            'correct_index' => 1,
            'explanation' => 'Antananarivo est la capitale.',
        ]]]);

        $questions = app(QuizGenerator::class)->generate(str_repeat('texte de cours ', 30), 1);

        $this->assertCount(1, $questions);
        $this->assertSame(1, $questions[0]['correct_index']);
        Http::assertSent(fn ($request) => $request->hasHeader('x-api-key', 'test-key'));
    }

    public function test_rejette_une_sortie_malformee(): void
    {
        $this->fakeApi(['questions' => [[
            'question' => 'Question avec seulement 2 choix',
            'choices' => ['A', 'B'],
            'correct_index' => 5,
            'explanation' => '...',
        ]]]);

        $this->expectException(RuntimeException::class);
        app(QuizGenerator::class)->generate(str_repeat('texte de cours ', 30), 1);
    }

    public function test_erreur_api_leve_une_exception(): void
    {
        Http::fake(['api.anthropic.com/*' => Http::response([], 500)]);

        $this->expectException(RuntimeException::class);
        app(QuizGenerator::class)->generate(str_repeat('texte de cours ', 30), 1);
    }
}
