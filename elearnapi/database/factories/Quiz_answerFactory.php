<?php

namespace Database\Factories;

use App\Models\Quizze;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz_answer>
 */
class Quiz_answerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'quiz_id' => Quizze::inRandomOrder()->first()?->id,
            
            'answer' => fake()->paragraph(),

            'is_correct' => fake()->boolean()
            
        ];
    }
}
