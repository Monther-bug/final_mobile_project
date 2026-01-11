<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Problem;
use App\Models\Solution;
use Illuminate\Database\Seeder;

class SolutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users and problems
        $adminUser = User::where('email', 'admin@example.com')->first();
        $demoUser = User::where('email', 'demo@example.com')->first();
        $problems = Problem::all();

        if (!$adminUser || !$demoUser || $problems->isEmpty()) {
            $this->command->warn('Skipping SolutionSeeder: Users or Problems not found.');
            return;
        }

        // Admin has solved many problems
        $adminSolvedProblems = $problems->take(15);
        foreach ($adminSolvedProblems as $problem) {
            Solution::create([
                'user_id' => $adminUser->id,
                'problem_id' => $problem->id,
                'code' => $this->getSampleCode($problem),
                'status' => 'passed',
                'output' => 'All test cases passed',
                'time_taken' => rand(60, 600),
                'memory_used' => rand(10, 50) . 'MB',
            ]);
        }

        // Demo user has solved some problems
        $demoSolvedProblems = $problems->take(8);
        foreach ($demoSolvedProblems as $index => $problem) {
            // Some passed, some failed
            $status = $index < 6 ? 'passed' : 'failed';
            Solution::create([
                'user_id' => $demoUser->id,
                'problem_id' => $problem->id,
                'code' => $this->getSampleCode($problem),
                'status' => $status,
                'output' => $status === 'passed' ? 'All test cases passed' : 'Test case 2 failed',
                'time_taken' => rand(120, 900),
                'memory_used' => rand(15, 60) . 'MB',
            ]);
        }

        // Create solutions for other users (for leaderboard variety)
        $otherUsers = User::whereNotIn('email', ['admin@example.com', 'demo@example.com', 'test@example.com'])->get();
        
        foreach ($otherUsers as $user) {
            // Each user solves a random number of problems
            $numToSolve = rand(3, 12);
            $problemsToSolve = $problems->random(min($numToSolve, $problems->count()));
            
            foreach ($problemsToSolve as $problem) {
                Solution::create([
                    'user_id' => $user->id,
                    'problem_id' => $problem->id,
                    'code' => $this->getSampleCode($problem),
                    'status' => 'passed',
                    'output' => 'All test cases passed',
                    'time_taken' => rand(60, 1200),
                    'memory_used' => rand(10, 80) . 'MB',
                ]);
            }
        }
    }

    private function getSampleCode(Problem $problem): string
    {
        $title = strtolower($problem->title);
        
        if (str_contains($title, 'sum')) {
            return "function sumArray(arr) {\n    return arr.reduce((a, b) => a + b, 0);\n}\n\n// correct solution";
        }
        
        if (str_contains($title, 'reverse')) {
            return "function reverse(input) {\n    return input.split('').reverse().join('');\n}\n\n// correct solution";
        }
        
        if (str_contains($title, 'palindrome')) {
            return "function isPalindrome(s) {\n    const clean = s.toLowerCase().replace(/[^a-z0-9]/g, '');\n    return clean === clean.split('').reverse().join('');\n}\n\n// correct solution";
        }
        
        if (str_contains($title, 'factorial')) {
            return "function factorial(n) {\n    if (n <= 1) return 1;\n    return n * factorial(n - 1);\n}\n\n// correct solution";
        }
        
        if (str_contains($title, 'fibonacci')) {
            return "function fibonacci(n) {\n    if (n <= 1) return n;\n    let a = 0, b = 1;\n    for (let i = 2; i <= n; i++) {\n        [a, b] = [b, a + b];\n    }\n    return b;\n}\n\n// correct solution";
        }

        return "function solve(input) {\n    // Solution implementation\n    return result;\n}\n\n// correct solution";
    }
}
