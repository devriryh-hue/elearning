<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Course;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('role', 'student')
                ->inRandomOrder()
                ->first()?->id,

            'course_id' => Course::inRandomOrder()
                ->first()?->id,

            'progress' => fake()->numberBetween(0, 100),

            'completed' => fake()->boolean()
        ];
    }
}
