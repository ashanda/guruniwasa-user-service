<?php

namespace App\Http\Controllers;

use App\Models\StudentSubject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentSubjectController extends Controller
{
    public function liveLessons()
    {
        $user = Auth::guard('student')->user();
        $userSubjets = StudentSubject:: where('student_id', $user->id)->get();  
        return response()->json(['status' => 200, 'message' => 'Logged in user subjects', 'data' => $userSubjets]);
    }


    public function studentSubjectRemove(Request $request)
    {
        try {
            // Retrieve the student subject record from the database
            $studentSubject = StudentSubject::where('student_id', $request->student_id)->first();

            if ($studentSubject) {
                // Decode the JSON subject_id array from the database
                $subjectIds = json_decode($studentSubject->subject_ids, true);

                // Check if the specific subject_id exists in the array
                if (($key = array_search($request->subject_id, $subjectIds)) !== false) {
                    // Remove the subject_id from the array
                    unset($subjectIds[$key]);

                    // Reindex the array (optional but recommended)
                    $subjectIds = array_values($subjectIds);

                    // Update the database with the modified array
                    $studentSubject->subject_ids = json_encode($subjectIds);
                    $studentSubject->save();
                }

                
                return response()->json([
                    'status' => 200,
                    'message' => 'Subject removed successfully',
                ], 200);
            } else {
                return response()->json(['error' => 'Student subject not found'], 404);
            }
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }

    public function studentSubjectAdd(Request $request)
    {
        Log::info($request->all());
        try {
            // Retrieve the student subject record from the database
            $studentSubject = StudentSubject::where('student_id', $request->student_id)->first();

            if ($studentSubject) {
                // Decode the JSON subject_id array from the database
                $subjectIds = json_decode($studentSubject->subject_ids, true);

                // Check if the specific subject_id already exists in the array
                if (!in_array($request->subject_id, $subjectIds)) {
                    // Add the new subject_id to the array
                    $subjectIds[] = $request->subject_id;

                    // Update the database with the modified array
                    $studentSubject->subject_ids = json_encode($subjectIds);
                    $studentSubject->save();
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Subject already exists in the list',
                        'data' => [],
                    ], 400);
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'Subject added successfully',
                    'data' => [
                        'subject' => $studentSubject,
                    ],
                ], 200);
            } else {
                // Create a new record if it doesn't exist
                $newSubjectArray = [$request->subject_id];
                $newStudentSubject = StudentSubject::create([
                    'student_id' => $request->student_id,
                    'subject_id' => json_encode($newSubjectArray),
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Subject added successfully',
                    'data' => [
                        'subject' => $newStudentSubject,
                    ],
                ], 200);
            }
        } catch (Exception $exception) {
            return response()->json([
                'status' => 400,
                'message' => $exception->getMessage(),
                'data' => [],
            ], 400);
        }
    }
}
