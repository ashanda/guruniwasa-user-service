<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Repositories\StaffRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    use ResponseTrait;

    protected $staff;

    public function __construct(StaffRepository $staff)
    {
        $this->staff = $staff;
    }
    public function logout(): JsonResponse
    {
        try {
            $user = Auth::guard('staff')->user();

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
            $user = Auth::guard('staff')->user();
            if ($user) {
                return response()->json(['status' => 200, 'message' => 'Authorized', 'data' => $user]);
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }


    public function index(): JsonResponse
    {
        try {
            $staffs = $this->staff->all();
            return response()->json(['status' => 200, 'data' => $staffs]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function store(StoreStaffRequest $request): JsonResponse
    {
        try {
            $data = $this->staff->create($request->all());
            return response()->json(['status' => 201, 'message' => 'Staff created successfully.', 'data' => $data]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = $this->staff->find($id);
            return response()->json(['status' => 200, 'data' => $data]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function update(UpdateStaffRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->staff->update($id, $request->all());
            return response()->json(['status' => 200, 'message' => 'Staff updated successfully.', 'data' => $data]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->staff->delete($id);
            return response()->json(['status' => 200, 'message' => 'Staff deleted successfully.']);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function trashed(): JsonResponse
    {
        try {
            $staffs = $this->staff->trashed();
            return response()->json(['status' => 200, 'data' => $staffs]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function restore($id): JsonResponse
    {
        try {
            $this->staff->restore($id);
            return response()->json(['status' => 200, 'message' => 'Staff restored successfully.']);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function forceDelete($id): JsonResponse
    {
        try {
            $this->staff->forceDelete($id);
            return response()->json(['status' => 200, 'message' => 'Staff permanently deleted.']);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
