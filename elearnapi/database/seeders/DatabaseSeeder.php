<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\LessonSeeder;
use Database\Seeders\EnrollmentSeeder;
use Database\Seeders\QuizzeSeeder;
use Database\Seeders\Quiz_answerSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'role' => 'professor'
        ]);

        User::factory(10)->create([
            'role' => 'student'
        ]);

        User::factory(3)->create([
            'role' => 'professor'
        ]);

        $this->call([
            CourseSeeder::class,
            LessonSeeder::class,    
            EnrollmentSeeder::class,
            QuizzeSeeder::class,
            Quiz_answerSeeder::class,
        ]);
    }
}
