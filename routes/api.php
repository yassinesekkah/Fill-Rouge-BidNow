<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;

Route::get('/test', function () {
    return response()->json([
        "message" => "API works"
    ]);
});

Route::get('/products', [ProductController::class, 'index']);

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class,'logout']);

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return $request->user();
});