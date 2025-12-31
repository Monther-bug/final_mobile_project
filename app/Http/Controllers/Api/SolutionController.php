<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SolutionResource;
use App\Models\Solution;
use App\Services\CodeValidationService;

/**
 * @OA\Tag(
 *     name="Solutions",
 *     description="API Endpoints of Solutions"
 * )
 */
class SolutionController extends BaseController
{
    /**
     * @OA\Post(
     *      path="/api/solutions",
     *      operationId="storeSolution",
     *      tags={"Solutions"},
     *      summary="Submit a solution",
     *      description="Submit code for a problem",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"problem_id","code"},
     *              @OA\Property(property="problem_id", type="integer"),
     *              @OA\Property(property="code", type="string"),
     *              @OA\Property(property="time_taken", type="integer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     * )
     */
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
            'time_taken' => $request->input('time_taken'),
        ]);

        $validationService = new CodeValidationService();
        $validationService->validate($solution);

        return new SolutionResource($solution);
    }

    public function update(Request $request, $id)
    {
        $solution = Solution::findOrFail($id);

        \Illuminate\Support\Facades\Gate::authorize('update', $solution);

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

        \Illuminate\Support\Facades\Gate::authorize('delete', $solution);

        $solution->delete();

        return response()->json(['message' => 'Solution deleted']);
    }

    public function history(Request $request)
    {
        $solutions = Solution::where('user_id', $request->user()->id)
            ->with(['problem:id,title'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return SolutionResource::collection($solutions);
    }
}
