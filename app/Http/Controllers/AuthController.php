<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('login', 'logout', 'register', 'me');
    }

    public function login(Request $request)
    {
        $credentials = request(['user_name', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'invalid credentials'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'token_type' => 'Bearer',
            'token' => $token,
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['status' => 'success']);
    }

    public function register(Request $request)
    {
        // $request->validate([
        //     'user_name' => 'required|string',
        //     'user_address' => 'required|string',
        //     'user_phone' => 'required|string',
        //     'user_email' => 'required|email',
        //     'password' => 'required|string',
        // ]);

        // 建立新使用者
        $user = User::create([
            'user_name' => $request->name,
            'user_address' => $request->address,
            'user_phone' => $request->phone,
            'user_email' => $request->email,
            'user_password' => bcrypt($request->password),
        ]);

        $token = auth()->login($user);
        $user->save();

        return response()->json([
            'status' => 'success',
            'token_type' => 'Bearer',
            'token' => $token,
        ]);
    }

    public function me(Request $request)
    {
        $token = $request->token;
        $user = JWTAuth::setToken($token)->toUser();
        if (count((array)$user) > 0) {
            return response()->json([
                'status' => 'success',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'fail'
            ], 401);
        }
    }
}
