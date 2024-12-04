<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\UserController;
use App\Models\User;

Auth::routes(['verify' => true]);

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    // ユーザーの確認リンクが正しいかチェック
    $user = User::findOrFail($id);

    // ハッシュが一致するか確認し、メールを確認済みとしてマーク
    if (hash_equals($hash, sha1($user->getEmailForVerification()))) {
        $user->markEmailAsVerified();

        // セッションを再生成
        $request = request(); // 現在のリクエストを取得
        $request->session()->regenerate();

        // ユーザーをログイン
        Auth::login($user);

        // 初回ログインの場合、プロフィール編集画面へ
        if ($user->first_login) {
            // 初回ログイン後、first_loginをfalseに更新
            $user->update(['first_login' => false]);

            return redirect('/mypage/profile'); // プロフィール編集画面にリダイレクト
        }

        // 通常のリダイレクト先
        return redirect()->intended('/'); // intendedでリダイレクト
    }

    return redirect()->route('home'); // 無効なリンクの場合はホームにリダイレクト
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
        Route::post('/purchase/{item_id}/stripe-session', [PurchaseController::class, 'stripeSession']);

        Route::get('/mypage', [MyPageController::class, 'show']);

        Route::get('/sell', [SellController::class, 'show']);
        Route::post('/sell', [SellController::class, 'store']);
    });
});


