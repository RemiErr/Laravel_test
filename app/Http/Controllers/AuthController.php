<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
        // 驗證資料格式
        $validator = Validator::make(
            $request->all(),
            [
                'user_name' => 'required|string',
                'password' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                // 請求語法的格式錯誤 (400 Bad Request)
                'status' => 400,
                'message' => 'Parameters Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // 比對資料內容
        $credentials = request(['user_name', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                // 請求未完成 (401 Bad Request)
                'status' => 401,
                'message' => 'Invalid Credentials'
            ], 401);
        }

        return response()->json([
            'status' => 200,
            'token_type' => 'Bearer',
            'token' => $token,
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['status' => 200]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_name' => 'required|string',
                'user_address' => 'required|string',
                'user_phone' => 'required|string',
                'user_email' => 'required|email',
                'password' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Parameters Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // 建立新使用者
        $user = User::create([
            'user_name' => $request->user_name,  
            'user_address' => $request->user_address,
            'user_phone' => $request->user_phone,
            'user_email' => $request->user_email,
            'user_password' => bcrypt($request->password),
        ]);

        $token = auth()->login($user);
        $user->save();

        return response()->json([
            'status' => 200,
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
                'status' => 200,
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 401
            ], 401);
        }
    }
}
