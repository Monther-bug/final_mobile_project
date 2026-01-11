<?php

namespace App\Http\Controllers\Api;

use App\Models\Problem;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Daily Challenge",
 *     description="API Endpoints for Daily Challenge"
 * )
 */
class DailyChallengeController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/daily-challenge",
     *     summary="Get today's daily challenge",
     *     tags={"Daily Challenge"},
     *     @OA\Response(
     *         response=200,
     *         description="Today's featured problem"
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Use the day of year as a seed for consistent daily selection
        $dayOfYear = Carbon::today()->dayOfYear;
        $year = Carbon::today()->year;
        
        // Get total problems count
        $totalProblems = Problem::count();
        
        if ($totalProblems === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No problems available',
            ], 404);
        }

        // Calculate which problem to show today (deterministic based on date)
        $problemIndex = (($year * 365) + $dayOfYear) % $totalProblems;
        
        // Get the problem at that index
        $problem = Problem::with('exercise:id,title,category')
            ->skip($problemIndex)
            ->first();

        if (!$problem) {
            $problem = Problem::with('exercise:id,title,category')->first();
        }

        // Check if user has already solved this problem today
        $isCompleted = false;
        if ($request->user()) {
            $isCompleted = $request->user()
                ->solutions()
                ->where('problem_id', $problem->id)
                ->where('status', 'passed')
                ->exists();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $problem->id,
                'title' => $problem->title,
                'difficulty' => $problem->difficulty,
                'category' => $problem->exercise->category ?? 'General',
                'exercise_title' => $problem->exercise->title ?? null,
                'exercise_id' => $problem->exercise_id,
                'is_completed' => $isCompleted,
                'points' => $this->getPointsForDifficulty($problem->difficulty),
                'date' => Carbon::today()->toDateString(),
            ],
        ]);
    }

    /**
     * Get points based on difficulty level
     */
    private function getPointsForDifficulty(string $difficulty): int
    {
        return match (strtolower($difficulty)) {
            'easy' => 10,
            'medium' => 25,
            'hard' => 50,
            default => 10,
        };
    }
}
