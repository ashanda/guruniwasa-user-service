<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginRequestStudent;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use ResponseTrait;

    private $serverApiKey;

    public function __construct(private AuthRepository $auth)
    {
       
        $this->serverApiKey = env('API_GATEWAY_KEY');
    }

    public function studentLogin(LoginRequestStudent $request): JsonResponse
    {
        return $this->handleLogin($request, 'Student');
    }

    public function teacherLogin(LoginRequest $request): JsonResponse
    {
        return $this->handleLogin($request, 'Teacher');
    }

    public function staffLogin(LoginRequest $request): JsonResponse
    {
        return $this->handleLogin($request, 'Staff');
    }

    public function adminLogin(LoginRequest $request): JsonResponse
    {
        return $this->handleLogin($request, 'Admin');
    }

    public function superadminLogin(LoginRequest $request): JsonResponse
    {
        return $this->handleLogin($request, 'Superadmin');
    }

    private function handleLogin($request, $tag): JsonResponse
    {
        $apiKey = $request->header('API-Key');
        
        if ($apiKey !== $this->serverApiKey) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized. Invalid API key.',
            ], 401);
        }

        try {
            $data = $this->auth->login($request->all(), $tag);
            return $this->responseSuccess($data, 'Logged in successfully.', 200);
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage(), 400);
        }
    }

    public function testInsertPerformance(): JsonResponse
    {
        $startTime = microtime(true);
        $batchSize = 1000; // Start with 1000 records per batch
        $totalInsertedRecords = 0;
        $maxTimeInSeconds = 1;

        while (true) {
            $records = [];

            for ($i = 0; $i < $batchSize; $i++) {
                $records[] = [
                    'name' => 'User ' . uniqid(),
                    'email' => uniqid() . '@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('users')->insert($records);
            $totalInsertedRecords += $batchSize;

            $currentTime = microtime(true);
            if (($currentTime - $startTime) >= $maxTimeInSeconds) {
                break;
            }
        }

        $executionTime = microtime(true) - $startTime;
        Log::info("Inserted $totalInsertedRecords records in $executionTime seconds.");

        return response()->json([
            'total_inserted_records' => $totalInsertedRecords,
            'execution_time' => $executionTime,
        ]);
    }
}
