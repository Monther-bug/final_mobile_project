<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ExerciseSeeder::class,
            SolutionSeeder::class,
            ProgressSeeder::class,
        ]);

        $this->command->info('Database seeding completed!');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('- Admin: admin@example.com / password');
        $this->command->info('- Demo:  demo@example.com / password');
        $this->command->info('- Test:  test@example.com / password');
    }
}
