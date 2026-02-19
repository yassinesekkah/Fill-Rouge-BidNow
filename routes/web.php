<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = \App\Models\Product::latest()->take(6)->get();
    return view('home', compact('products'));
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/seller/dashboard', function () {
        return 'Seller Dashboard';
    });
     Route::get('/seller-test', function () {
        return 'Welcome Seller';
    });
});


Route::middleware(['auth', 'role:buyer'])->group(function () {
    //
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    //
});

require __DIR__.'/auth.php';