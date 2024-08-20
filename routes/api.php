<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

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

// User Router
Route::post('/register', [AuthController::class, 'register']);
Route::get('/user', [AuthController::class, 'getAllUsers']);
Route::get('/user/{id}', [AuthController::class, 'getUser']);
Route::put('/user/{id}', [AuthController::class, 'editUser']);
Route::delete('/user/{id}', [AuthController::class, 'deleteUser']);

// Product Router
Route::post('/product', [ProductController::class, 'createProduct']);
Route::get('/product', [ProductController::class, 'getAllProducts']);
Route::get('/product/{id}', [ProductController::class, 'getProduct']);
Route::put('/product/{id}', [ProductController::class, 'editProduct']);
Route::delete('/product/{id}', [ProductController::class, 'deleteProduct']);

// Order Router
Route::post('/order', [OrderController::class, 'createOrder']);
Route::get('/order', [OrderController::class, 'getAllOrders']);
Route::get('/order/{id}', [OrderController::class, 'getOrder']);
Route::put('/order/{id}', [OrderController::class, 'editOrder']);
Route::delete('/order/{id}', [OrderController::class, 'deleteOrder']);
