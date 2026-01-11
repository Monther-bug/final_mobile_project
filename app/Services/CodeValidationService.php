<?php

namespace App\Services;

use App\Models\Solution;
use App\Models\Progress;

class CodeValidationService
{
    public function validate(Solution $solution)
    {
        $problem = $solution->problem;
        $testCases = $problem->testCases;
        
        $passed = true;
        $lastOutput = '';

        foreach ($testCases as $testCase) {
            $lastOutput = $this->simulateExecution($solution->code, $testCase->input, $testCase->expected_output);

            if ($lastOutput !== $testCase->expected_output) {
                $passed = false;
                break;
            }
        }

        $solution->update([
            'status' => $passed ? 'passed' : 'failed',
            'output' => $lastOutput,
        ]);

        if ($passed) {
            // Update user streak
            $solution->user->updateStreak();
            
            // Award points based on difficulty
            $points = match (strtolower($problem->difficulty ?? 'easy')) {
                'easy' => 10,
                'medium' => 25,
                'hard' => 50,
                default => 10,
            };
            
            // Only award points if this is the first time solving this problem
            $previouslySolved = Solution::where('user_id', $solution->user_id)
                ->where('problem_id', $solution->problem_id)
                ->where('id', '!=', $solution->id)
                ->where('status', 'passed')
                ->exists();
            
            if (!$previouslySolved) {
                $solution->user->increment('total_points', $points);
            }

            Progress::updateOrCreate(
                [
                    'user_id' => $solution->user_id,
                    'exercise_id' => $solution->problem->exercise_id,
                ],
                [
                    'is_completed' => true,
                ]
            );
        }
    }

    private function simulateExecution($code, $input, $expectedOutput)
    {
        // Placeholder Logic:
        // If the user's code contains the word 'correct', we simulate that it produced the expected output.
        // Otherwise, it produced something else.
        if (str_contains(strtolower($code), 'correct')) {
            return $expectedOutput;
        }
        
        return 'ERROR: Output does not match.';
    }
}
