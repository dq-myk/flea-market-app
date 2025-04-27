<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserReviewController;

// メール認証の通知を再送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth'])->name('verification.send');

// メール認証確認
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

// メール認証が必要なページ
Route::get('/mypage/profile', function () {
    return view('profile_edit');
})->middleware(['auth', 'verified']);

// 未認証ユーザーがアクセスできるルート
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [UserController::class, 'register']);

// 認証後のユーザーがアクセスするルート
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/mypage/profile', [UserController::class, 'show']);
        Route::post('/mypage/profile', [UserController::class, 'profile']);


        Route::post('/item/{item}/like', [ItemController::class, 'like']);
        Route::post('/item/{item_id}/comment', [ItemController::class, 'comment']);

        Route::get('/purchase/{item_id}', [PurchaseController::class, 'show']);
        Route::post('/purchase/{item_id}/confirm', [PurchaseController::class, 'confirm']);
        Route::post('/purchase/{item_id}/complete', [PurchaseController::class, 'complete']);
        Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit']);
        Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'address']);

        Route::get('/sell', [SellController::class, 'show']);
        Route::post('/sell', [SellController::class, 'store']);

        Route::get('/mypage', [MyPageController::class, 'show']);
        Route::get('/chat/seller/{transaction}', [MyPageController::class, 'seller']);
        Route::get('/chat/buyer/{transaction}', [MyPageController::class, 'buyer']);

        Route::post('/chat/send/{transaction}', [MessageController::class, 'send']);
        Route::get('/chat/{transactionId}', [MessageController::class, 'showChat']);
        Route::post('/chat/action', [MessageController::class, 'action']);
        Route::post('/chat/update/{id}', [MessageController::class, 'update']);

        Route::post('/transaction/complete/{transaction}', [TransactionController::class, 'complete']);

        Route::post('/review/complete/{id}', [UserReviewController::class, 'reviewComplete']);
    });

