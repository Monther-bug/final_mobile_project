<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;

class ExerciseController extends Controller
{
    public function index()
    {
        return ExerciseResource::collection(Exercise::all());
    }

    public function show($id)
    {
        return new ExerciseResource(Exercise::with('problems')->findOrFail($id));
    }
}
