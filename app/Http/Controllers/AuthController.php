<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'user_password' => 'required|string',
        ]);

        // 帳號密碼登入
        $credentials = request(['username', 'password']);

        // 登入錯誤
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // 取得請求體的 User 資料
        $user = $request->user();

        // Json Web Token
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at->toDateTimeString(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'user_address' => 'required|string',
            'user_phone' => 'required|string',
            'user_email' => 'required|email',
            'user_password' => 'required|string',
        ]);

        // 建立新使用者
        $user = User::create([
            'name' => $request->user_name,
            'address' => $request->user_address,
            'phone' => $request->user_phone,
            'email' => $request->user_email,
            'password' => $request->user_password,
        ]);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at->toDateTimeString(),
        ]);
    }
}
