<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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

    public function show()
    {
        $user = Auth::user();
        return view('profile_edit', compact('user'));
    }

    //プロフィール内容をデータベースに保存)
    public function profile(ProfileRequest $request)
    {
        $user = auth()->user();
        $user->name = $request->name;
        $user->post_code = $request->post_code;
        $user->address = $request->address;
        $user->building = $request->building;

        // 画像の処理
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imagePath = $image->store('public/images');
            $user->image_path = str_replace('public/', 'storage/', $imagePath);
        }

        $user->save();

        return redirect('/');
    }
}