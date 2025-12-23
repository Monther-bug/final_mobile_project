<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Exercise;
use App\Models\Problem;
use App\Models\TestCase;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a default Admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Create 5 random Users
        User::factory(5)->create();

        // 3. Create Exercises with Problems and TestCases
        $categories = ['Array Basics', 'String Manipulation', 'Logical Thinking'];

        foreach ($categories as $category) {
            $exercise = Exercise::factory()->create([
                'title' => $category,
                'category' => 'Algorithms',
            ]);

            // Create 3 Problems for each Exercise
            $problems = Problem::factory(3)->create([
                'exercise_id' => $exercise->id,
            ]);

            foreach ($problems as $problem) {
                // Create 2 TestCases for each Problem
                TestCase::factory()->create([
                    'problem_id' => $problem->id,
                    'input' => '1, 2',
                    'expected_output' => '3', // Example for summation
                ]);

                TestCase::factory()->create([
                    'problem_id' => $problem->id,
                    'input' => '10, 20',
                    'expected_output' => '30',
                ]);
            }
        }
    }
}
