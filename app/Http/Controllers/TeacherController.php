<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function index(): JsonResponse
    {
        $teachers = Teacher::all();
        foreach ($teachers as $teacher) {
            $teacher->grades = DB::table('grade_teacher')->where('teacher_id', $teacher->id)->pluck('grade_id');
            $teacher->subjects = DB::table('subject_teacher')->where('teacher_id', $teacher->id)->pluck('subject_id');
        }
        return response()->json(['status' => 200, 'data' => $teachers]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:teachers',
            'name' => 'required',
            'grades' => 'required|array',
            'subjects' => 'required|array',
            'medium' => 'required',
            'address' => 'required',
            'district' => 'required',
            'town' => 'required',
            'contact_no' => 'required',
            'secondary_contact_no' => 'nullable',
            'email' => 'required|email|unique:teachers',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()]);
        }

        $teacher = Teacher::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'medium' => $request->medium,
            'address' => $request->address,
            'district' => $request->district,
            'town' => $request->town,
            'contact_no' => $request->contact_no,
            'secondary_contact_no' => $request->secondary_contact_no,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Sync grades and subjects manually
        foreach ($request->grades as $grade) {
            DB::table('grade_teacher')->insert([
                'teacher_id' => $teacher->id,
                'grade_id' => $grade
            ]);
        }

        foreach ($request->subjects as $subject) {
            DB::table('subject_teacher')->insert([
                'teacher_id' => $teacher->id,
                'subject_id' => $subject
            ]);
        }

        return response()->json(['status' => 201, 'data' => $teacher]);
    }

    public function show($id): JsonResponse
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json(['status' => 404, 'message' => 'Teacher not found']);
        }

        $teacher->grades = DB::table('grade_teacher')->where('teacher_id', $teacher->id)->pluck('grade_id');
        $teacher->subjects = DB::table('subject_teacher')->where('teacher_id', $teacher->id)->pluck('subject_id');

        return response()->json(['status' => 200, 'data' => $teacher]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json(['status' => 404, 'message' => 'Teacher not found']);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:teachers,user_id,' . $teacher->id,
            'name' => 'required',
            'grades' => 'required|array',
            'subjects' => 'required|array',
            'medium' => 'required',
            'address' => 'required',
            'district' => 'required',
            'town' => 'required',
            'contact_no' => 'required',
            'secondary_contact_no' => 'nullable',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()]);
        }

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $teacher->update($data);

        // Sync grades and subjects manually
        DB::table('grade_teacher')->where('teacher_id', $teacher->id)->delete();
        DB::table('subject_teacher')->where('teacher_id', $teacher->id)->delete();

        foreach ($request->grades as $grade) {
            DB::table('grade_teacher')->insert([
                'teacher_id' => $teacher->id,
                'grade_id' => $grade
            ]);
        }

        foreach ($request->subjects as $subject) {
            DB::table('subject_teacher')->insert([
                'teacher_id' => $teacher->id,
                'subject_id' => $subject
            ]);
        }

        return response()->json(['status' => 200, 'data' => $teacher]);
    }

    public function destroy($id): JsonResponse
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json(['status' => 404, 'message' => 'Teacher not found']);
        }

        DB::table('grade_teacher')->where('teacher_id', $teacher->id)->delete();
        DB::table('subject_teacher')->where('teacher_id', $teacher->id)->delete();
        $teacher->delete();

        return response()->json(['status' => 200, 'message' => 'Teacher deleted successfully']);
    }

    public function logout(): JsonResponse
    {
        try {
            $user = Auth::guard('teacher')->user();

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
            $user = Auth::guard('teacher')->user();
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
