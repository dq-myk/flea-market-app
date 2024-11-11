<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\My_pageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\UserController;

// 未認証ユーザーがアクセスできるルート
Route::get('/login', [AuthenticatedSessionController::class, 'create']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// ユーザー登録ページのルート
Route::get('/register', [UserController::class, 'show']);
Route::post('/register', [UserController::class, 'register']);

// 認証後のユーザーがアクセスするルート
Route::middleware('auth')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::get('/search', [ItemController::class, 'search']);
    Route::get('/item/{item_id}', [ItemController::class, 'show']);
    Route::post('/mypage/profile', [My_pageController::class, 'profile']);
    // Route::post('/item/{item_id}', [ItemController::class, 'detail']);
});



