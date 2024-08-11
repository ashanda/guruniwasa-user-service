<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class AttendnceMarkController extends Controller
{
    public function attendence( Request $request){
        
        try {
            $staffs = [];
            return response()->json(['status' => 200, 'data' => $staffs]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
