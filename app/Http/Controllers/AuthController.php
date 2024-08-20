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
        $this->middleware('auth:api')->except('login', 'logout', 'register', 'userId', 'editUser', 'delUser', 'getAllUsers', 'getUser');
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

    // public function me(Request $request)
    // {
    //     $token = $request->token;
    //     $user = JWTAuth::setToken($token)->toUser();
        
    //     if (count((array)$user) > 0) {
    //         return response()->json([
    //             'status' => 200,
    //             'user' => $user
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 401
    //         ], 401);
    //     }
    // }
    
    function getAllUsers(Request $request) {
        $users = User::all();

        return response()->json([
            'status' => 200,
            'data' => $users,
            'token_type' => 'Bearer',
        ]);
    }

    function getUser($id) {
        $user = User::findOrFail($id);

        return response()->json([
            'status' => 200,
            'data' => $user,
            'token_type' => 'Bearer',
        ]);
        
    }

    public function userId(Request $request)
    {
        $token = $request->token;
        $user = JWTAuth::setToken($token)->toUser();
        
        if (count((array)$user) > 0) {
            return response()->json([
                'status' => 200,
                'user_id' => $user->user_id
            ]);
        } else {
            return response()->json([
                'status' => 401
            ], 401);
        }
    }

    public function editUser(Request $request, $id) {
        $validator = Validator::make(
            $request->all(),
            [
                'user_name' => 'required|string',
                'user_address' => 'string',
                'user_phone' => 'string',
                'user_email' => 'email',
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

        $user = User::find($id);
        $user->update($request->all());

        return response()->json([
            'status' => 200,
            'token_type' => 'Bearer',
        ]);
    }

    public function delUser($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 400,
                'message' => 'User does not exist',
            ], 400);
        }

        $user->delete();
        // auth()->logout();

        return response()->json([
            'status' => 200,
            'token_type' => 'Bearer',
        ]);
    }
}
