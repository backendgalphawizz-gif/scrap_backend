<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AdminAuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        // Attempt login
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

       
        $user = User::select('id', 'name', 'email','role_id','image')
            ->where('id', Auth::id())
            ->first();

        // Check role
        if ($user->role_id !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Admin access only.'
            ], 403);
        }

        // Create Passport Token
        $token = $user->createToken('AdminToken')->accessToken;

        return response()->json([
            'status' => true,
            'message' => 'Admin login successful',
            'token' => $token,
            'user' => [
            'id'        => $user->id ?? null,
            'name'      => $user->name ?? null,
            'email'     => $user->email ?? null,
            'image'     => $user->image?? null,
            'role_id'   => $user->role_id ?? null,
            'role_type' => $user->role->name ?? null,
        ]
        ]);
    }
}
