<?php

namespace App\Http\Controllers;

use App\Models\StudentSubject;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherSubjectController extends Controller
{
    public function liveLessons()
    {
        $teacher = Auth::guard('teacher')->user();
        $teacherSubjets = TeacherSubject:: where('teacher_id', $teacher->id)->get();  
        return response()->json(['status' => 200, 'message' => 'Logged in teacher subjects', 'data' => $teacherSubjets]);
    }

    public function studentSubjectWithCount(Request $request){

        $request->validate([
            'subject_id' => 'required',
        ]);
        // Retrieve the subject_id from the request
    $subjectId = $request->input('subject_id');

    // Get students who are associated with the given subject ID
    $teacherSubjects = StudentSubject::whereJsonContains('subject_ids', $subjectId)
        ->with('student') // Assuming there's a relationship with 'student' in StudentSubject model
        ->get();

        // Count the number of students for the subject
        $studentCount = $teacherSubjects->count();

        return response()->json([
            'status' => 200, 
            'message' => 'Logged in teacher subjects', 
            'data' => $teacherSubjects,
            'student_count' => $studentCount
        ]);
        }
}
