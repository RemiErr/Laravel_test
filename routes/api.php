<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// JWT 登入用，定義登入登出路由
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'me']);

// 暫時先取得當前ID
Route::get('/user/id', [AuthController::class, 'userId']);

// Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

// 註冊
Route::post('/register', [AuthController::class, 'register']);

// 編輯
Route::put('/user/{id}', [AuthController::class, 'editUser']);
Route::delete('/user/{id}', [AuthController::class, 'delUser']);


// Product Router
Route::post('/product', [ProductController::class, 'create']);
Route::put('/product/{id}', [ProductController::class, 'editProd']);
Route::delete('/product/{id}', [ProductController::class, 'delProd']);
