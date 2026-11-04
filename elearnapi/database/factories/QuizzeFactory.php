<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Lesson;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quizze>
 */
class QuizzeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $correct = fake()->word();

        $options = [
            [
                'text' => $correct,
                'correct' => true,
            ]
        ];

        for ($i = 0; $i < 3; $i++) {
            $options[] = [
                'text' => fake()->word(),
                'correct' => false,
            ];
        }

        shuffle($options);
        $points = fake()->numberBetween(1, 5);
        return [
            //
            'lesson_id' => Lesson::inRandomOrder()->first()?->id,

            'question' => fake()->sentence(),

            //'options' => json_encode($options),
            'options' => $options,
            'points' => $points

        ];
    }
}
