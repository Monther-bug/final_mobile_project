<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SolutionResource;
use App\Models\Solution;
use App\Services\CodeValidationService;

class SolutionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'problem_id' => 'required|exists:problems,id',
            'code' => 'required|string',
        ]);

        $solution = Solution::create([
            'user_id' => $request->user()->id,
            'problem_id' => $validated['problem_id'],
            'code' => $validated['code'],
            'status' => 'pending', 
        ]);

        $validationService = new CodeValidationService();
        $validationService->validate($solution);

        return new SolutionResource($solution);
    }

    public function update(Request $request, $id)
    {
        $solution = Solution::findOrFail($id);

        if ($solution->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $solution->update([
            'code' => $validated['code'],
        ]);

        return new SolutionResource($solution);
    }

    public function destroy(Request $request, $id)
    {
        $solution = Solution::findOrFail($id);

        if ($solution->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $solution->delete();

        return response()->json(['message' => 'Solution deleted']);
    }
}
