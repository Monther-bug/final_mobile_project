<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProblemResource;
use App\Models\Problem;

/**
 * @OA\Tag(
 *     name="Problems",
 *     description="API Endpoints of Problems"
 * )
 */

class ProblemController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/api/problems/{id}",
     *      operationId="getProblemById",
     *      tags={"Problems"},
     *      summary="Get problem information",
     *      description="Returns problem data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Problem id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     * )
     */
    public function show($id)
    {
        $problem = Problem::with('testCases')->find($id);
  
        if (is_null($problem)) {
            return $this->sendError('Problem not found.');
        }
   
        return $this->sendResponse(new ProblemResource($problem), 'Problem retrieved successfully.');
    }

    /**
     * @OA\Get(
     *      path="/api/problems/{id}/hint",
     *      operationId="getProblemHint",
     *      tags={"Problems"},
     *      summary="Get problem hint",
     *      description="Returns problem hint",
     *      @OA\Parameter(
     *          name="id",
     *          description="Problem id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     * )
     */
    public function hint($id)
    {
        $problem = Problem::find($id);

        if (is_null($problem)) {
            return $this->sendError('Problem not found.');
        }

        return $this->sendResponse(['hint' => $problem->hint], 'Hint retrieved successfully.');
    }
}
