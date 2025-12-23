<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;

/**
 * @OA\Tag(
 *     name="Exercises",
 *     description="API Endpoints of Exercises"
 * )
 */

class ExerciseController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/api/exercises",
     *      operationId="getExercisesList",
     *      tags={"Exercises"},
     *      summary="Get list of exercises",
     *      description="Returns list of exercises",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     * )
     */
    public function index()
    {
        $exercises = Exercise::paginate(10);
        return ExerciseResource::collection($exercises);
    }

    /**
     * @OA\Get(
     *      path="/api/exercises/{id}",
     *      operationId="getExerciseById",
     *      tags={"Exercises"},
     *      summary="Get exercise information",
     *      description="Returns exercise data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Exercise id",
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
        $exercise = Exercise::with('problems')->find($id);
  
        if (is_null($exercise)) {
            return $this->sendError('Exercise not found.');
        }
   
        return $this->sendResponse(new ExerciseResource($exercise), 'Exercise retrieved successfully.');
    }
}
