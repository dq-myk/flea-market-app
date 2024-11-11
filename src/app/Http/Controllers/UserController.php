<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;  // RegisterRequest をインポート
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 登録フォームの表示
    public function show()
    {
        return view('auth.register');  // 'register.blade.php' ビューを表示
    }

    // ユーザー登録処理
    public function register(RegisterRequest $request)
    {
        // バリデーション後のデータを取得
        $validated = $request->validated();

        // ユーザーを作成
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 登録後にログインページへリダイレクト
        return redirect('/login');
    }
}