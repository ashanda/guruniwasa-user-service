<?php

namespace App\Http\Controllers;

use App\Models\StudentSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSubjectController extends Controller
{
    public function liveLessons()
    {
        $user = Auth::guard('student')->user();
        $userSubjets = StudentSubject:: where('student_id', $user->id)->get();  
        return response()->json(['status' => 200, 'message' => 'Logged in user subjects', 'data' => $userSubjets]);
    }
}
