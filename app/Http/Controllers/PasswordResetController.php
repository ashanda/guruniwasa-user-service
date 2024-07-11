<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\AuthRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function resetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validate token
        $passwordReset = DB::table('password_reset_tokens')
                            ->where('email', $user->email)
                            ->where('token', $request->reset_token)
                            ->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        // Reset password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete password reset token
        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->delete();

        return response()->json(['message' => 'Password reset successful']);
    }

    public function requestPasswordReset(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $existingToken = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();
        
        if ($existingToken) {
            // Update the existing token
            DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->update([
                    'token' => Str::random(60),
                    'created_at' => Carbon::now(),
                ]);

            $token = $existingToken->token;

        }else{
              // Generate a new reset token
        $token = Str::random(60);;
        DB::table('password_reset_tokens')->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);

        }

       
        // Return password reset toke in the API response
        return response()->json(['reset_token' => $token]);
    }
}
