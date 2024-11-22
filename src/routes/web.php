<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\UserController;

// 未認証ユーザーがアクセスできるルート
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [UserController::class, 'register']);

// 認証後のユーザーがアクセスするルート
Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [UserController::class, 'show']);
    Route::post('/mypage/profile', [UserController::class, 'profile']);

    Route::get('/?tab=mylist', [ItemController::class, 'index']);
    Route::get('/search', [ItemController::class, 'search']);
    Route::post('/item/{item}/like', [ItemController::class, 'like']);
    Route::post('/item/{item_id}/comment', [ItemController::class, 'comment']);

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase']);
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'address']);

    Route::get('/mypage', [MyPageController::class, 'show']);

    Route::get('/sell', [SellController::class, 'show']);
    Route::post('/sell', [SellController::class, 'store']);
});



