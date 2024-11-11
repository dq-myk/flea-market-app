<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    // ログインフォームを表示する
    public function create()
    {
        return view('auth.login');  // ログインフォームのビューを返す
    }

    public function store(LoginRequest $request)
    {
        // 認証の失敗時に特定のエラーメッセージを追加
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'login_error' => 'ログイン情報が登録されていません。',
            ]);
        }

        // プロフィールが未完成の場合は、profile_editにリダイレクト
        if (session('profile_incomplete')) {
            session()->forget('profile_incomplete');
            return redirect()->route('profile.edit');
        }

        // ログイン成功後のリダイレクト
        return redirect()->intended('/');
    }
}
