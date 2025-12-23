<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProblemResource;
use App\Models\Problem;

class ProblemController extends Controller
{
    public function show($id)
    {
        return new ProblemResource(Problem::with('testCases')->findOrFail($id));
    }

    public function hint($id)
    {
        $problem = Problem::findOrFail($id);
        return response()->json(['hint' => $problem->hint]);
    }
}
