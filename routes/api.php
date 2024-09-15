<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendnceMarkController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentReviewController;
use App\Http\Controllers\StudentSubjectController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherSubjectController;
use App\Http\Controllers\UserController;
use App\Models\StudentSubject;
use App\Models\Superadmin;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Logins
Route::post('/students-login', [LoginController::class, 'Studentlogin']);
Route::post('/teachers-login', [LoginController::class, 'Teacherlogin']);
Route::post('/staff-login', [LoginController::class, 'Stafflogin']);
Route::post('/admins-login', [LoginController::class, 'Adminlogin']);
Route::post('/super-admins-login', [LoginController::class, 'Superadminlogin']);

Route::middleware('auth:student')->post('student/logout', [StudentController::class, 'logout']);
Route::middleware('auth:teacher')->post('teacher/logout', [TeacherController::class, 'logout']);
Route::middleware('auth:staff')->post('staff/logout', [StaffController::class, 'logout']);
Route::middleware('auth:admin')->post('admin/logout', [AdminController::class, 'logout']);
Route::middleware('auth:superadmin')->post('super-admin/logout', [SuperAdminController::class, 'logout']);


Route::middleware('auth:student')->get('student/check-auth', [StudentController::class, 'checkAuth']);
Route::middleware('auth:teacher')->get('teacher/check-auth', [TeacherController::class, 'checkAuth']);
Route::middleware('auth:staff')->get('staff/check-auth', [StaffController::class, 'checkAuth']);

Route::middleware('auth:superadmin')->group(function () {
    Route::get('staff/trashed', [StaffController::class, 'trashed'])->name('staff.trashed');
    Route::post('staff/restore/{id}', [StaffController::class, 'restore'])->name('staff.restore');
    Route::delete('staff/force-delete/{id}', [StaffController::class, 'forceDelete'])->name('staff.forceDelete');
    Route::Resource('staff', StaffController::class);


    Route::resource('teachers', TeacherController::class);
});

Route::get('teacher/data/{id}', [TeacherController::class,'showTeacher'])->name('teacher.showTeacher');


Route::middleware('auth:admin')->get('admin/check-auth', [AdminController::class, 'checkAuth']);
Route::middleware('auth:superadmin')->get('super-admin/check-auth', [SuperAdminController::class, 'checkAuth']);

//student register
Route::post('/student-register', [RegisterController::class, 'Studentregister']);
Route::post('/teacher-register', [RegisterController::class, 'Teacherregister']);





Route::post('/register', [RegisterController::class, 'register']);
Route::post('/password/reset/request', [PasswordResetController::class, 'requestPasswordReset']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.reset');
Route::get('/test-insert-performance',  [LoginController::class,'testInsertPerformance']);

Route::get('/get-teacher',[TeacherController::class,'getTeacher']);

Route::get('/single-student', [StudentController::class, 'singleStudent']);
Route::get('/single-teacher', [TeacherController::class, 'singleTeacher']);

Route::middleware('auth:student')->get('/live-lesson', [StudentSubjectController::class, 'liveLessons']);
Route::middleware('auth:teacher')->get('/live-lesson-teacher', [TeacherSubjectController::class, 'liveLessons']);
Route::middleware(['auth:teacher'])->post('/attendence', [AttendnceMarkController::class, 'attendence']);

Route::middleware('auth:student')->post('/student-subject-remove', [StudentSubjectController::class, 'studentSubjectRemove']);
Route::middleware('auth:student')->post('/student-subject-add', [StudentSubjectController::class, 'studentSubjectAdd']);

Route::middleware('auth:student')->get('teacher-review', [StudentReviewController::class, 'getTeacherSubjects']);

Route::middleware(['auth:teacher'])->get('/student-subject-with-count', [TeacherSubjectController::class, 'studentSubjectWithCount']);

Route::middleware('auth:api')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index']);
    
    Route::post('logout', [ProfileController::class, 'logout']);
});


