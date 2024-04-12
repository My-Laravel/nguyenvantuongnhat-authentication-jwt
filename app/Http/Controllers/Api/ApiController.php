<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    //register api routes here
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'true',
            'message' => 'Successfully registered',
        ], 201);
    }

    //login api routes here
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $token = JWTAuth::attempt($request->only('email', 'password'));

        if (!empty($token)) {
            return response()->json([
                'status' => 'true',
                'message' => 'Successfully logged in',
                'token' => $token,
            ], 201);
        }

        return response()->json([
           'status' => 'false',
           'message' => 'Invalid credentials',
        ], 401);
    }
}
