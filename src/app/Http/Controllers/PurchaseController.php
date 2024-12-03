<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
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

    //選択した支払方法を小計欄へ反映
    public function confirm(PurchaseRequest $request, $item)
    {
        $validated = $request->validated();

        $itemData = Item::findOrFail($item);

        return view('purchase', [
            'paymentMethod' => $validated['payment_method'],
            'item' => $itemData,
            'user' => auth()->user(),
        ]);
    }

    //購入処理
    public function complete(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        // 購入処理の実行
        $purchase = new Purchase();
        $purchase->user_id = auth()->id();
        $purchase->item_id = $item->id;
        $purchase->save();

        // 商品一覧画面にリダイレクト
        return redirect('/');
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

    // Stripe(決済処理画面)設定
    public function stripeSession(PurchaseRequest $request, $item_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/'),
            'cancel_url' => url("/purchase/{$item_id}"),
        ]);

        return redirect($session->url);
    }
}