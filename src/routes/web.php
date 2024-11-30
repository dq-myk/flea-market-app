<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\UserController;

Auth::routes(['verify' => true]);

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    // 実際には、ユーザーの確認と処理を行います
    return 'Email Verified!';
})->name('verification.verify');

// 未認証ユーザーがアクセスできるルート
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [UserController::class, 'register']);

// 認証後のユーザーがアクセスするルート
Route::middleware(['verified'])->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/mypage/profile', [UserController::class, 'show']);
        Route::post('/mypage/profile', [UserController::class, 'profile']);


        Route::post('/item/{item}/like', [ItemController::class, 'like']);
        Route::post('/item/{item_id}/comment', [ItemController::class, 'comment']);

        Route::get('/purchase/{item_id}', [PurchaseController::class, 'show']);
        Route::post('/purchase/{item_id}/confirm', [PurchaseController::class, 'confirm']);
        Route::post('/purchase/{item_id}/complete', [PurchaseController::class, 'complete']);
        Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit']);
        Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'address']);

        Route::get('/mypage', [MyPageController::class, 'show']);

        Route::get('/sell', [SellController::class, 'show']);
        Route::post('/sell', [SellController::class, 'store']);
    });
});


