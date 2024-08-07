<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use ResponseTrait;

    private AuthRepository $auth;

    public function __construct(AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    public function logout(): JsonResponse
    {
        try {
            $guard = Auth::guard(); // Get the current guard

            if ($guard->check()) {
                $user = $guard->user();

                // Revoke the user's current token
                $token = $user->token();
                $token->revoke();

                // Optionally, delete the token from the database
                $token->delete();

                return $this->responseSuccess('', 'User logged out successfully!', 200);
            } else {
                return $this->responseError([], 'Unauthorized', 401);
            }
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage(), 401);
        }
    }
}
