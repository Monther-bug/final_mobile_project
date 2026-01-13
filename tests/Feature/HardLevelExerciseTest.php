<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\Problem;
use Database\Seeders\ExerciseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HardLevelExerciseTest extends TestCase
{
    use RefreshDatabase;

    public function test_exercise_seeder_creates_hard_level_exercises()
    {
        // Run the seeder
        $this->seed(ExerciseSeeder::class);

        // Assert Hard Challenges category exists
        $this->assertDatabaseHas('exercises', [
            'category' => 'Hard Challenges',
        ]);

        // Assert Hard difficulty problem exists
        $this->assertDatabaseHas('problems', [
            'difficulty' => 'hard',
            'title' => 'Median of Arrays',
        ]);

        $this->assertDatabaseHas('problems', [
            'difficulty' => 'hard',
            'title' => 'Trapping Rain Water',
        ]);

        // Check relationship
        $exercise = Exercise::where('category', 'Hard Challenges')->first();
        $this->assertNotNull($exercise);
        $this->assertTrue($exercise->problems()->where('difficulty', 'hard')->exists());
    }
}
