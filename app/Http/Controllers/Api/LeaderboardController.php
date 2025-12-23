<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Leaderboard",
 *     description="API Endpoints of Leaderboard"
 * )
 */
class LeaderboardController extends BaseController
{
    public function index()
    {
        // Simple leaderboard: Users ranked by number of processed (passed) solutions
        // For scalability, this should be cached or aggregate updated in background.
        $users = User::withCount(['solutions' => function ($query) {
            $query->where('status', 'passed');
        }])
        ->orderBy('solutions_count', 'desc')
        ->orderBy('total_points', 'desc')
        ->take(10)
        ->get();

        return response()->json($users);
    }
}
