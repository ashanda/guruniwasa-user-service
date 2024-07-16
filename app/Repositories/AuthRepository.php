<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Superadmin;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\PersonalAccessTokenResult;
use Illuminate\Support\Str;

class AuthRepository
{
    public function login(array $data, string $tag): array
    {
        $user = $this->getUserByEmail($data['email'], $tag);

        if (!$user) {
            throw new Exception("Sorry, user does not exist.", 404);
        }

        if (!$this->isValidPassword($user, $data, $tag)) {
            throw new Exception("Sorry, password does not match.", 401);
        }

        $tokenInstance = $this->createAuthToken($user, $tag);

        return $this->getAuthData($user, $tokenInstance);
    }

    public function register(array $data): array
    {
        $user = User::create($this->prepareDataForRegistration($data));

        if (!$user) {
            throw new Exception("Sorry, user was not registered. Please try again.", 404);
        }

        $tokenInstance = $this->createAuthToken($user, 'User'); // Default to 'User'

        return $this->getAuthData($user, $tokenInstance);
    }

    public function Studentregister(array $data): array
    {
        $user = Student::create($this->prepareDataForRegistrationstudent($data));

        
        
        if (!$user) {
            throw new Exception("Sorry, Student was not registered. Please try again.", 404);
        }
        $studentSubjects = StudentSubject::create([
            'student_id' => $user->id,
            'subject_ids' => $data['subject']
        ]);

        $tokenInstance = $this->createAuthToken($user, 'Student'); // Default to 'User'

        return $this->getAuthData($user, $tokenInstance);
    }

    public function getUserByEmail(string $email, string $tag)
    {
        switch ($tag) {
            case 'Student':
                return Student::where('username', $email)->first();
            case 'Teacher':
                return Teacher::where('email', $email)->first();
            case 'Staff':
                return Staff::where('email', $email)->first();
            case 'Admin':
                return Admin::where('email', $email)->first();
            case 'Superadmin':
                return Superadmin::where('email', $email)->first();
            default:
                return User::where('email', $email)->first();
        }
    }

    public function isValidPassword($user, array $data, string $tag): bool
    {
        switch ($tag) {
            case 'Student':
                return $this->isValidPasswordStudent($user, $data);
            case 'Teacher':
                return $this->isValidPasswordTeacher($user, $data);
            case 'Staff':
                return $this->isValidPasswordStaff($user, $data);
            case 'Admin':
                return $this->isValidPasswordAdmin($user, $data);
            case 'Superadmin':
                return $this->isValidPasswordSuperadmin($user, $data);
            default:
                return $this->isValidPasswordUser($user, $data);
        }
    }

    public function isValidPasswordUser(User $user, array $data): bool
    {
        return Hash::check($data['password'], $user->password);
    }

    public function isValidPasswordStudent(Student $user, array $data): bool
    {
        return Hash::check($data['password'], $user->password);
    }

    public function isValidPasswordTeacher(Teacher $user, array $data): bool
    {
        return Hash::check($data['password'], $user->password);
    }

    public function isValidPasswordStaff(Staff $user, array $data): bool
    {
        return Hash::check($data['password'], $user->password);
    }

    public function isValidPasswordAdmin(Admin $user, array $data): bool
    {
        return Hash::check($data['password'], $user->password);
    }

    public function isValidPasswordSuperadmin(Superadmin $user, array $data): bool
    {
        return Hash::check($data['password'], $user->password);
    }

    public function createAuthToken($user, string $tag): PersonalAccessTokenResult
    {
        switch ($tag) {
            case 'Student':
                return $user->createToken('authToken_Student');
            case 'Teacher':
                return $user->createToken('authToken_Teacher');
            case 'Staff':
                return $user->createToken('authToken_Staff');
            case 'Admin':
                return $user->createToken('authToken_Admin');
            case 'Superadmin':
                return $user->createToken('authToken_Superadmin');
            default:
                return $user->createToken('authToken');
        }
    }

    public function getAuthData($user, PersonalAccessTokenResult $tokenInstance): array
    {
        $userData = [
            'access_token' => $tokenInstance->accessToken,
            'expires_at' => Carbon::parse($tokenInstance->token->expires_at)->toDateTimeString()
        ];

        // Determine the type of user object and prepare data accordingly
        if ($user instanceof Student) {
            $userData['user'] = [
                'id' => $user->id,
                
                'usernmae' => $user->username,
                'role' => 'student', // Example: Add specific role or other attributes
            ];
        } elseif ($user instanceof Teacher) {
            $userData['user'] = [
                'id' => $user->id,
                
                'email' => $user->email,
                'role' => 'teacher', // Example: Add specific role or other attributes
            ];
         } elseif ($user instanceof Staff) {
            $userData['user'] = [
                'id' => $user->id,
                
                'email' => $user->email,
                'role' => 'staff', // Example: Add specific role or other attributes
            ];     
        } elseif ($user instanceof Admin) {
            $userData['user'] = [
                'id' => $user->id,
                
                'email' => $user->email,
                'role' => 'admin', // Example: Add specific role or other attributes
            ];
         } elseif ($user instanceof Superadmin) {
            $userData['user'] = [
                'id' => $user->id,
                
                'email' => $user->email,
                'role' => 'super admin', // Example: Add specific role or other attributes
            ];    
        } else {
            // Default handling for generic User or other types
            $userData['user'] = [
                'id' => $user->id,
                
                'email' => $user->email,
                'role' => 'user', // Example: Add specific role or other attributes
            ];
        }

        return $userData;
    }

    public function prepareDataForRegistration(array $data): array
    {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];
    }

    public function prepareDataForRegistrationStudent(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'full_name' => $data['full_name'],
            'student_code' => $data['student_code'],
            'birthday' => $data['birthday'],
            'gender' => $data['gender'],
            'address' => $data['address'],
            'school' => $data['school'],
            'district' => $data['district'],
            'city' => $data['city'],
            'parent_phone' => $data['parent_phone'],
            'grade' => $data['grade'],
           
        ];
    }
    

    public function createPasswordResetToken(User $user): string
    {
        $token = Str::random(60); // Generate a random token

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        return $token;
    }
}
