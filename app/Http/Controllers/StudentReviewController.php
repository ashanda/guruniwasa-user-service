<?php

namespace App\Http\Controllers;

use App\Models\TeacherSubject;
use Exception;
use Illuminate\Http\Request;

class StudentReviewController extends Controller
{
    public function getTeacherSubjects(Request $request)
    {
        try {
            // Fetch records where the subject_id is present in the subject_ids array
            $datas = TeacherSubject::whereJsonContains('subject_ids', $request->subject_id)->with('review')->with('teacher')->get();

            return response()->json([
                'status' => 200,
                'message' => 'Subjects retrieved successfully',
                'data' => $datas,
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'status' => 400,
                'message' => $exception->getMessage(),
                'data' => [],
            ], 400);
        }
    }
}
