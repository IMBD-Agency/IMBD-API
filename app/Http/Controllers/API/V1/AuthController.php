<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller {
    public function login(LoginRequest $request) {
        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = auth()->user();
            Log::info($user->name . ' loged in.');
            return response()->json([
                'status' => 'success',
                'data' => [
                    'user' => $user->only(['name', 'email', 'mobile', 'image']),
                    'token' => $user->createToken('Token for ' . $user->name)->plainTextToken,
                ]
            ], 200);
        } else {
            Log::info('Login failed for ' . $request->email);
            return response()->json([
                'status' => 'error',
                'message' => 'Crediantials not matched.',
            ], 401);
        }
    }

    public function logout() {
        Auth::user()->currentAccessToken()->delete();
        Log::info(auth()->user()->name . ' trying to logout.');
        return response()->json([
            'status' => 'success',
            'message' => 'Logout successfull.',
        ], 200);
    }
}
