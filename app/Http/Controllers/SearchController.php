<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function studentSearch(){
        try {
            $students = Student::with('subjects')->get();
            return response()->json(['status' => 200, 'message' => 'Authorized', 'data' => $students]);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function teacherSearch(){
        try {
            $teachers = Teacher::with('subjects')->get();
            return response()->json(['status' => 200, 'message' => 'Authorized', 'data' => $teachers]);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    

    
}
