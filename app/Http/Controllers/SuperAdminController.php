<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function logout(): JsonResponse
    {
        try {
            $user = Auth::guard('superadmin')->user();

            if ($user) {
                $token = $user->token();
                $token->revoke();
                $token->delete();
                return response()->json(['status' => 200, 'message' => 'logged out successfully']);
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function checkAuth(): JsonResponse
    {
        try {
            $user = Auth::guard('superadmin')->user();
            if ($user) {
                return response()->json(['status' => 200, 'message' => 'Authorized', 'data' => $user]);
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
