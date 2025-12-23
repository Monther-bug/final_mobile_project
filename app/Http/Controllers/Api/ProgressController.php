<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Progress;

class ProgressController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
        ]);

        $progress = Progress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'exercise_id' => $validated['exercise_id'],
            ],
            [
                'is_completed' => true,
            ]
        );

        return response()->json(['message' => 'Progress updated', 'progress' => $progress]);
    }
}
