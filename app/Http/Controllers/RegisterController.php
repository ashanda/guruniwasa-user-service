<?php

namespace App\Http\Controllers;


use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterRequestStudent;
use App\Http\Requests\RegisterRequestTeacher;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
     use ResponseTrait;

    public function __construct(private AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    public function register(RegisterRequest $request)
    {
        
        try {
            $data = $this->auth->register($request->all());

            return $this->responseSuccess($data, 'User created successfully.',201);
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage(),401);
        }
    }

    public function Studentregister(RegisterRequestStudent $request){
         try {
            $data = $this->auth->Studentregister($request->all());

            return $this->responseSuccess($data, 'User created successfully.',201);
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage(),401);
        }
    }

    public function Teacherregister(RegisterRequestTeacher $request){
         try {
            $data = $this->auth->Teacherregister($request->all());

            return $this->responseSuccess($data, 'User created successfully.',201);
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage(),401);
        }
    }
    

}
