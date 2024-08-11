<?php

namespace App\Http\Controllers;

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
}
