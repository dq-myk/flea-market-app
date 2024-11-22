<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Sell;

class MyPageController extends Controller
{
    //マイページで出品と購入を表示
    public function show(Request $request)
    {
        $tab = $request->query('tab', 'sell'); // デフォルトは'sell'
        $user = auth()->user(); // ログイン中のユーザーを取得
        $items = [];

        if ($tab === 'sell') {
            // 出品した商品を取得
            $items = $user->sells()->with('item')->get()->pluck('item');
        } elseif ($tab === 'purchase') {
            // 購入した商品を取得
            $items = $user->purchases()->with('item')->get()->pluck('item');
        }

        return view('profile', compact('user', 'tab', 'items'));
    }
}
