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

        // フォーム送信後の支払方法を取得、デフォルト値は '未選択'
        $paymentMethod = $request->input('payment_method', '未選択');

        if ($request->isMethod('post')) {
            if ($item->purchases()->exists()) {
                return redirect('/');
            }

            $purchase = new Purchase();
            $purchase->user_id = $user->id;
            $purchase->item_id = $item->id;
            $purchase->save();

            return redirect("/");
        }

        // ビューをレンダリング
        return view('purchase', compact('item', 'user', 'paymentMethod'));
    }

    //配送先変更画面を表示
    public function edit($item_id)
    {
        $user = auth()->user();
        $item = Item::findOrFail($item_id);

        return view('purchase_address', compact('user', 'item'));
    }

    //配送先変更処理
    public function address(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $user = auth()->user();
        $user->post_code = $request->post_code;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect("/purchase/{$item_id}");
    }
}