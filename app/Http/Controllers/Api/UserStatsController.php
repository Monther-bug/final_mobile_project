<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Problem;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="User Stats",
 *     description="API Endpoints for User Statistics"
 * )
 */
class UserStatsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/user/stats",
     *     summary="Get user statistics",
     *     tags={"User Stats"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User statistics including problems solved, streak, points, and rank"
     *     )
     * )
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        
        // Count problems solved (unique problems with passed solutions)
        $problemsSolved = $user->solutions()
            ->where('status', 'passed')
            ->distinct('problem_id')
            ->count('problem_id');

        // Calculate user's rank (based on total_points)
        $rank = User::where('total_points', '>', $user->total_points)->count() + 1;

        // Get streak info
        $currentStreak = $user->current_streak ?? 0;

        return response()->json([
            'success' => true,
            'data' => [
                'problems_solved' => $problemsSolved,
                'current_streak' => $currentStreak,
                'streak_label' => $currentStreak . ' ' . ($currentStreak === 1 ? 'Day' : 'Days'),
                'total_points' => $user->total_points ?? 0,
                'rank' => $rank,
                'longest_streak' => $user->longest_streak ?? 0,
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/user/profile",
     *     summary="Get complete user profile",
     *     tags={"User Stats"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Complete user profile with stats"
     *     )
     * )
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        // Count problems solved
        $problemsSolved = $user->solutions()
            ->where('status', 'passed')
            ->distinct('problem_id')
            ->count('problem_id');

        // Count exercises completed
        $exercisesCompleted = $user->progress()
            ->where('is_completed', true)
            ->count();

        // Calculate user's rank
        $rank = User::where('total_points', '>', $user->total_points)->count() + 1;

        // Total users for rank context
        $totalUsers = User::count();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rank' => $rank,
                'total_users' => $totalUsers,
                'total_points' => $user->total_points ?? 0,
                'problems_solved' => $problemsSolved,
                'exercises_completed' => $exercisesCompleted,
                'current_streak' => $user->current_streak ?? 0,
                'longest_streak' => $user->longest_streak ?? 0,
                'member_since' => $user->created_at->format('F Y'),
            ],
        ]);
    }
}
