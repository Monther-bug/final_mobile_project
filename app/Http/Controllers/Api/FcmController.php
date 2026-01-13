<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FcmController extends Controller
{
    /**
     * Update the authenticated user's FCM token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateToken(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = $request->user();
        if ($user) {
            $user->fcm_token = $request->token;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'FCM token updated successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not authenticated',
        ], 401);
    }
}
