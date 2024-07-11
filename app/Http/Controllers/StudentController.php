<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function logout(): JsonResponse
    {
        try {
            $user = Auth::guard('student')->user();

            if ($user) {
                $token = $user->token();
                $token->revoke();
                $token->delete();
                return response()->json(['message' => 'logged out successfully'], 200);
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
