<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;


Route::get('/test', function () {
    return response()->json([
        "message" => "API works"
    ]);
});

Route::get('/products', [ProductController::class, 'index']);