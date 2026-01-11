<?php

namespace App\Services;

use App\Models\Solution;
use App\Models\Progress;
use Illuminate\Support\Facades\Log;

class CodeValidationService
{
    private PythonExecutorService $executor;

    public function __construct()
    {
        $this->executor = new PythonExecutorService();
    }

    public function validate(Solution $solution): array
    {
        $problem = $solution->problem;
        $testCases = $problem->testCases;
        
        // Get function name and input type from problem
        $functionName = $problem->function_name;
        $inputType = $problem->input_type ?? 'auto';
        
        $allPassed = true;
        $results = [];
        $totalExecutionTime = 0;
        $failedTestCase = null;

        foreach ($testCases as $index => $testCase) {
            $result = $this->executor->execute(
                $solution->code, 
                $testCase->input,
                $functionName,
                $inputType
            );
            
            $totalExecutionTime += $result['execution_time'];
            
            // Normalize outputs for comparison
            $expectedOutput = $this->normalizeOutput($testCase->expected_output);
            $actualOutput = $this->normalizeOutput($result['output']);
            
            $passed = $result['success'] && $expectedOutput === $actualOutput;
            
            $results[] = [
                'test_case' => $index + 1,
                'input' => $testCase->input,
                'expected' => $testCase->expected_output,
                'actual' => $result['output'],
                'passed' => $passed,
                'error' => $result['error'] ?? null,
                'execution_time' => $result['execution_time'],
            ];

            if (!$passed && $allPassed) {
                $allPassed = false;
                $failedTestCase = [
                    'test_case' => $index + 1,
                    'input' => $testCase->input,
                    'expected' => $testCase->expected_output,
                    'actual' => $result['output'],
                    'error' => $result['error'] ?? null,
                ];
            }
        }

        // Build output message
        $passedCount = count(array_filter($results, fn($r) => $r['passed']));
        $totalCount = count($results);
        
        if ($allPassed) {
            $outputMessage = "All {$totalCount} test cases passed! ✓";
        } else {
            $outputMessage = "Passed {$passedCount}/{$totalCount} test cases.\n\n";
            if ($failedTestCase) {
                $outputMessage .= "Failed Test Case {$failedTestCase['test_case']}:\n";
                $outputMessage .= "Input: {$failedTestCase['input']}\n";
                $outputMessage .= "Expected: {$failedTestCase['expected']}\n";
                $outputMessage .= "Got: {$failedTestCase['actual']}";
                if ($failedTestCase['error']) {
                    $outputMessage .= "\nError: {$failedTestCase['error']}";
                }
            }
        }

        // Update solution
        $solution->update([
            'status' => $allPassed ? 'passed' : 'failed',
            'output' => $outputMessage,
            'time_taken' => (int) $totalExecutionTime,
        ]);

        // Handle successful solution
        if ($allPassed) {
            $this->handleSuccessfulSolution($solution, $problem);
        }

        return [
            'status' => $allPassed ? 'passed' : 'failed',
            'message' => $outputMessage,
            'test_results' => $results,
            'total_execution_time' => $totalExecutionTime,
        ];
    }

    /**
     * Normalize output for comparison
     */
    private function normalizeOutput(string $output): string
    {
        // Trim whitespace, normalize line endings
        $output = trim($output);
        $output = str_replace("\r\n", "\n", $output);
        $output = preg_replace('/\s+$/', '', $output); // Remove trailing whitespace from each line
        return $output;
    }

    /**
     * Handle successful solution - update streak, points, progress
     */
    private function handleSuccessfulSolution(Solution $solution, $problem): void
    {
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

        // Update progress
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
