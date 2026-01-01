<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogger;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);
    
            $user = User::where('email', $request->email)->first();
    
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }
    
            $token = $user->createToken('api-token')->plainTextToken;
    
            AuditLogger::log(
                'login_success',
                $user->id
            );
    
            return response()->json([
                'token' => $token,
                'user'  => $user,
            ]);
        } catch (\Throwable $th) {
            AuditLogger::log(
                'login_failed',
                null,
                ['email' => $request->email]
            );
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        AuditLogger::log(
            'logout',
            auth()->id()
        );

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
