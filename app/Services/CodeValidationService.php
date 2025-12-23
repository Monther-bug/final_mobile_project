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

        foreach ($testCases as $testCase) {
            $output = $this->simulateExecution($solution->code, $testCase->input, $testCase->expected_output);

        if ($output !== $testCase->expected_output) {
                $passed = false;
                break;
            }
        }

        $solution->update([
            'status' => $passed ? 'passed' : 'failed',
        ]);

        if ($passed) {
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
