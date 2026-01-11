<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Exercise;
use App\Models\Progress;
use Illuminate\Database\Seeder;

class ProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        $demoUser = User::where('email', 'demo@example.com')->first();
        $exercises = Exercise::all();

        if (!$adminUser || !$demoUser || $exercises->isEmpty()) {
            $this->command->warn('Skipping ProgressSeeder: Users or Exercises not found.');
            return;
        }

        // Admin has completed most exercises
        foreach ($exercises->take(8) as $exercise) {
            Progress::create([
                'user_id' => $adminUser->id,
                'exercise_id' => $exercise->id,
                'is_completed' => true,
            ]);
        }

        // Demo user has completed some exercises
        foreach ($exercises->take(4) as $exercise) {
            Progress::create([
                'user_id' => $demoUser->id,
                'exercise_id' => $exercise->id,
                'is_completed' => true,
            ]);
        }

        // Create progress for other users
        $otherUsers = User::whereNotIn('email', ['admin@example.com', 'demo@example.com', 'test@example.com'])->get();
        
        foreach ($otherUsers as $user) {
            $numCompleted = rand(1, min(6, $exercises->count()));
            $completedExercises = $exercises->random($numCompleted);
            
            foreach ($completedExercises as $exercise) {
                Progress::create([
                    'user_id' => $user->id,
                    'exercise_id' => $exercise->id,
                    'is_completed' => true,
                ]);
            }
        }
    }
}
