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
            $user = Auth::guard('student')->user();
            if ($user) {
                // Load the student_subjects relationship
                $user->load(['subjects' => function($query) {
                    $query->select('student_id', 'subject_ids', 'status');
                }]);

                // Decode the subject_ids for each student subject
                $user->subjects->each(function($subjects) {
                    $subjects->subject_ids = json_decode($subjects->subject_ids);
                });

                return response()->json(['status' => 200, 'message' => 'Authorized', 'data' => $user]);
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
