<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\ProfileRequestRequest;

class My_pageController extends Controller
{
    //プロフィール内容をデータベースに保存)
    public function profile(ProfileRequest $request)
    {
        $user = auth()->user();

        if ($request->hasFile('profile_image')) {
            // 古い画像の削除（必要な場合）
            if ($user->image_path) {
                Storage::delete($user->image_path);
            }

            // 新しい画像を保存
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->image_path = $path;
        }

        // 他のフィールドを更新
        $user->fill($request->only(['name', 'post_code', 'address', 'building']));
        $user->save();

        return redirect()->route('index')
    }
}
