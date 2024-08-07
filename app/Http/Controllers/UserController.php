<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $users = Auth::user();
        try{
            return $this->responseSuccess($users, 'dashboard', 200);
        }catch(Exception $e){
            return $this->responseError([], $e->getMessage(), 400);
        }
    }
}
