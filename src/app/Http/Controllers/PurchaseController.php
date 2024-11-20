<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    //購入手続き画面を表示
    public function show(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        return view('purchase', compact('item', 'user'));
    }

    // 購入処理
    public function purchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        // 支払方法を取得、デフォルト値は '未選択'
        $paymentMethod = $request->input('payment_method', '未選択'); // フォーム送信後の支払方法を取得

        // バリデーションを実行
        $validated = $request->validate([
            'payment_method' => 'required|string',  // 支払い方法が選択されているかを確認
        ]);

        // 購入情報を保存
        if ($request->isMethod('post')) {
            if ($item->purchases()->exists()) {
                return redirect('/');
            }

            $purchase = new Purchase();
            $purchase->user_id = $user->id;
            $purchase->item_id = $item->id;
            $purchase->save();

            // 購入完了後に商品詳細ページにリダイレクト
            return redirect("/"); // Redirect to the home page or item listing page
        }

        // ビューをレンダリング
        return view('purchase', compact('item', 'user', 'paymentMethod'));
    }

    //配送先変更画面を表示
    public function edit($item_id)
    {
        $user = auth()->user(); // ログインユーザー情報を取得
        $item = Item::findOrFail($item_id); // item_idに対応するアイテムを取得

        return view('purchase_address', compact('user', 'item'));
    }

    //配送先変更処理
    public function address(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id); // 商品を取得

        // ログイン中のユーザーの配送先情報を更新
        $user = auth()->user();
        $user->post_code = $request->post_code;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect("/purchase/{$item_id}");
    }
}