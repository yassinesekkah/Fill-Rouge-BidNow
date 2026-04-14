<?php

use App\Http\Controllers\Api\AuctionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\api\ReviewController;
use Illuminate\Http\Request;

Route::get('/test', function () {
    return response()->json([
        "message" => "API works"
    ]);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return $request->user();
});

////===> Product Routes <====\\\\
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::get('/my-products', [ProductController::class, 'myProducts']);
});

////===> Auction Routes <====\\\\
Route::get('/auctions', [AuctionController::class, 'index']);
Route::get('/auctions/{id}', [AuctionController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/products/{id}/auction', [AuctionController::class, 'store']);
    Route::post('/auctions/{auction}/accept', [AuctionController::class, 'accept']);
    Route::post('/auctions/{auction}/reject', [AuctionController::class, 'reject']);
});

////===> bid Routes <====\\\\
Route::get('/auctions/{auction}/bids', [BidController::class, 'index']);
Route::get('/auctions/{auction}/highest-bid', [BidController::class, 'highestBid']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auctions/{auction}/bids', [BidController::class, 'store']);
});

////===> Review Routes <====\\\\
Route::get('/users/{user}/reviews', [ReviewController::class, 'userReviews']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auctions/{auction}/review', [ReviewController::class, 'store']);
});

////===> Review Routes <====\\\\
Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('auth:sanctum', 'role:admin')->group(function () {

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

});
