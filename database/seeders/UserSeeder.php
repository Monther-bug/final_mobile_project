<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'total_points' => 500,
            'current_streak' => 15,
            'longest_streak' => 30,
            'last_solved_at' => now()->subDay(),
        ]);

        // Create demo user
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'total_points' => 150,
            'current_streak' => 5,
            'longest_streak' => 10,
            'last_solved_at' => now(),
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'total_points' => 0,
            'current_streak' => 0,
            'longest_streak' => 0,
            'last_solved_at' => null,
        ]);

        // Create additional users with varying stats for leaderboard
        $users = [
            ['name' => 'Alice Johnson', 'points' => 850, 'streak' => 25, 'longest' => 45],
            ['name' => 'Bob Smith', 'points' => 720, 'streak' => 12, 'longest' => 20],
            ['name' => 'Charlie Brown', 'points' => 650, 'streak' => 8, 'longest' => 15],
            ['name' => 'Diana Prince', 'points' => 580, 'streak' => 20, 'longest' => 35],
            ['name' => 'Edward Norton', 'points' => 450, 'streak' => 3, 'longest' => 12],
            ['name' => 'Fiona Apple', 'points' => 380, 'streak' => 7, 'longest' => 14],
            ['name' => 'George Lucas', 'points' => 320, 'streak' => 0, 'longest' => 8],
            ['name' => 'Helen Troy', 'points' => 280, 'streak' => 4, 'longest' => 10],
            ['name' => 'Ivan Petrov', 'points' => 220, 'streak' => 2, 'longest' => 6],
            ['name' => 'Julia Roberts', 'points' => 180, 'streak' => 1, 'longest' => 5],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => strtolower(str_replace(' ', '.', $userData['name'])) . '@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'total_points' => $userData['points'],
                'current_streak' => $userData['streak'],
                'longest_streak' => $userData['longest'],
                'last_solved_at' => $userData['streak'] > 0 ? now()->subDays(rand(0, 1)) : null,
            ]);
        }
    }
}
